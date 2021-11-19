<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Presentation\Application\ContactForm;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use GuzzleHttp\Psr7\Uri;
use Hoerbiger\HoerbigerCom\Domain\Contact\CustomerGroupIdentifier;
use Hoerbiger\HoerbigerCom\Domain\Contact\Salutation;
use Hoerbiger\Shared\Application\Country;
use Hoerbiger\Shared\Application\CountryDirectory;
use Neos\ContentRepository\Domain\NodeAggregate\NodeAggregateIdentifier;
use Neos\ContentRepository\Domain\NodeType\NodeTypeConstraints;
use Neos\ContentRepository\Domain\NodeType\NodeTypeName;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodes;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\I18n\Locale;
use Neos\Flow\I18n\Service as LocalizationService;
use Neos\Flow\Security\Context as SecurityContext;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use Sitegeist\CsvPO\Domain\TranslationLabelSourceRepository;

final class ContactFormFactory extends AbstractComponentPresentationObjectFactory
{
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
     * @Flow\Inject
     * @var SecurityContext
     */
    protected $securityContext;

    public function create(TraversableNodeInterface $site, string $language): ContactForm
    {
        return new ContactForm(
            $this->securityContext->getCsrfProtectionToken(),
            $language,
            $this->createTargetUri(),
            $this->createOptions($site, $language),
            $this->createLabels($site, $language)
        );
    }

    private function createTargetUri(): Uri
    {
        $uriBuilder = clone $this->uriService->getControllerContext()->getUriBuilder();
        $uriBuilder->setFormat('json');

        return new Uri($uriBuilder->uriFor(
            'send',
            [],
            'ContactRequest',
            'Hoerbiger.HoerbigerCom',
            'Application'
        ));
    }

    /**
     * @return array<string,array<int,CustomerGroupIdentifier|NodeAggregateIdentifier|Salutation|string>>
     */
    private function createOptions(TraversableNodeInterface $site, string $language): array
    {
        $locale = new Locale($language);
        $applicationWorlds = [];
        foreach ($this->findApplicationWorlds($site) as $applicationWorld) {
            $applicationWorlds[] = $applicationWorld->getNodeAggregateIdentifier();
        }

        $countries = $this->countryDirectory->findAll($locale);
        $countries = $countries->sortForLanguage($language);

        return [
            'customerGroups' => CustomerGroupIdentifier::cases(),
            'applicationWorlds' => $applicationWorlds,
            'salutations' => Salutation::cases(),
            'addressCountries' => array_keys(array_map(function (Country $country): string {
                return $country->getCode();
            }, $countries->getCountries()))
        ];
    }

    /**
     * @return array<string,string>
     */
    private function createLabels(TraversableNodeInterface $site, string $language): array
    {
        $locale = new Locale($language);
        $labels = [];
        $labelSource = $this->translationLabelSourceRepository->findOneByIdentifier(
            'resource://Hoerbiger.HoerbigerCom/Private/Fusion/Presentation/Application/ContactForm/ContactForm.labels.csv'
        );
        foreach ($labelSource ? $labelSource->findAllTranslationLabels() : [] as $label) {
            $labels[$label->getIdentifier()] = (string)$label->findTranslationForLocaleChain(
                $this->localizationService->getLocaleChain($locale)
            ) ?: $label->getIdentifier();
        }
        foreach ($this->countryDirectory->findAll($locale) as $country) {
            $labels['addressCountry.' . $country->getCode()] = $country->getName();
        }
        foreach ($this->findApplicationWorlds($site) as $applicationWorld) {
            $labels['applicationWorld.' . $applicationWorld->getNodeAggregateIdentifier()] = $applicationWorld->getLabel();
        }

        return $labels;
    }

    /**
     * @return TraversableNodes<string,TraversableNodeInterface>
     */
    private function findApplicationWorlds(TraversableNodeInterface $site): TraversableNodes
    {
        return $site->findChildNodes(
            new NodeTypeConstraints(
                false,
                [
                    NodeTypeName::fromString('Hoerbiger.HoerbigerCom.ApplicationWorlds:Document.ApplicationWorld')
                ]
            )
        );
    }
}
