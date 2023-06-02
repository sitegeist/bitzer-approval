<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Domain\Contact;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use Hoerbiger\Shared\Application\CountryDirectory;
use Neos\ContentRepository\Domain\NodeAggregate\NodeAggregateIdentifier;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\ContentRepository\Domain\Service\ContextFactoryInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\I18n\Locale;
use Neos\Flow\I18n\Service as LocalizationService;
use Neos\Fusion\View\FusionView;
use Neos\SwiftMailer\Message;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\UriServiceInterface;
use Sitegeist\CsvPO\Domain\TranslationLabelSourceRepository;

/**
 * @Flow\Scope("singleton")
 */
final class Messenger
{
    const FORM_BASEPATH = 'resource://Hoerbiger.HoerbigerCom/Private/Fusion/Presentation/Application/ContactForm';

    /**
     * @Flow\Inject
     * @var UriServiceInterface
     */
    protected $uriService;

    /**
     * @Flow\Inject
     * @var ContextFactoryInterface
     */
    protected $contentContextFactory;

    /**
     * @Flow\Inject
     * @var TranslationLabelSourceRepository
     */
    protected $translationLabelSourceRepository;

    /**
     * @Flow\Inject
     * @var LocalizationService
     */
    protected $localizationService;

    /**
     * @Flow\Inject
     * @var CountryDirectory
     */
    protected $countryDirectory;

    /**
     * @Flow\InjectConfiguration(path="providerNotification.recipient")
     * @var array<string,string>
     */
    protected $providerRecipient;

    public function sendProviderNotification(SendContactRequest $command): void
    {
        $view = new FusionView();
        $view->setControllerContext($this->uriService->getControllerContext());
        $view->setFusionPathPattern(self::FORM_BASEPATH . '/Email');
        $view->setFusionPath('providerNotification');
        $customerGroupIdentifier = $command->getCustomerGroup();
        $applicationWorldIdentifier = $command->getApplicationWorld();
        $addressCountry = $command->getAddressCountry();

        $view->assignMultiple([
            'requestDate' => (new \DateTimeImmutable())->format('d.m.Y'),
            'language' => $command->getLanguage(),
            'customerGroup' => $customerGroupIdentifier
                ? $this->findCustomerGroupLabel($customerGroupIdentifier)
                : null,
            'applicationWorld' => $applicationWorldIdentifier
                ? $this->findApplicationWorldLabel($applicationWorldIdentifier)
                : null,
            'salutation' => $this->findLabel(
                'salutation.' . ($command->getSalutation()?->value ?: 'none'),
                $command->getLanguage()
            ),
            'givenName' => $command->getGivenName(),
            'familyName' => $command->getFamilyName(),
            'email' => $command->getEmailAddress(),
            'worksFor' => $command->getWorksFor(),
            'telephone' => $command->getTelephone(),
            'streetAddress' => $command->getStreetAddress(),
            'postalCode' => $command->getPostalCode(),
            'addressLocality' => $command->getAddressLocality(),
            'addressCountry' => $addressCountry
                ? $this->countryDirectory->findCountryName(
                    $addressCountry,
                    new Locale($command->getLanguage())
                )
                : null,
            'message' => $command->getMessage()
        ]);

        $message = new Message(
            'Neue Kontaktanfrage',
            $view->render(),
            'text/html'
        );
        $message->setFrom('noreply@hoerbiger.com');
        $message->setTo($this->resolveProviderRecipient($command));

        $message->send();
    }

    private function findCustomerGroupLabel(CustomerGroupIdentifier $customerGroupIdentifier): string
    {
        return $this->findLabel('customerGroup.' . $customerGroupIdentifier->value, 'de');
    }

    private function findLabel(string $identifier, string $language): string
    {
        $labelSource = $this->translationLabelSourceRepository->findOneByIdentifier(
            self::FORM_BASEPATH . '/ContactForm.labels.csv'
        );
        if ($labelSource) {
            $label = $labelSource->findTranslationLabelByIdentifier($identifier);
            if ($label) {
                $result = (string)$label->findTranslationForLocaleChain(
                    $this->localizationService->getLocaleChain(new Locale($language))
                );
                if ($result) {
                    return $result;
                }
            }
        }

        return $identifier;
    }

    private function findApplicationWorldLabel(NodeAggregateIdentifier $applicationWorldIdentifier): string
    {
        $contentContext = $this->contentContextFactory->create([
            'dimensions' => [
                'language' => ['de', 'en']
            ],
            'targetDimensions' => [
                'language' => 'de'
            ]
        ]);

        /** @var TraversableNodeInterface|null $applicationWorld */
        $applicationWorld = $contentContext->getNodeByIdentifier((string)$applicationWorldIdentifier);

        return $applicationWorld
            ? $applicationWorld->getLabel()
            : (string)$applicationWorldIdentifier;
    }

    /**
     * @return array<string,string>
     */
    private function resolveProviderRecipient(SendContactRequest $command): array
    {
        return [
            $this->providerRecipient['mail'] => $this->providerRecipient['name'],
        ];
    }

    public function sendCustomerConfirmationMessage(SendContactRequest $command): void
    {
        $view = new FusionView();
        $view->setControllerContext($this->uriService->getControllerContext());
        $view->setFusionPathPattern(self::FORM_BASEPATH . '/Email');
        $view->setFusionPath('customerConfirmation');

        $view->assignMultiple([
            'salutation' => \str_replace(
                ['{givenName}', '{familyName}'],
                [$command->getGivenName(), $command->getFamilyName()],
                $this->findLabel(
                    'customerConfirmation.salutation.' . $command->getSalutation()?->value,
                    $command->getLanguage()
                )
            ),
            'message' => $this->findLabel('customerConfirmation.message', $command->getLanguage()),
            'closing' => $this->findLabel('customerConfirmation.closing', $command->getLanguage())
        ]);

        $message = new Message(
            $this->findLabel('customerConfirmation.subject', $command->getLanguage()),
            $view->render(),
            'text/html'
        );
        $message->setFrom('noreply@hoerbiger.com');
        $fullName = $command->getGivenName() . ' ' . $command->getFamilyName();
        $message->setTo([$command->getEmailAddress() => $fullName]);

        $message->send();
    }
}
