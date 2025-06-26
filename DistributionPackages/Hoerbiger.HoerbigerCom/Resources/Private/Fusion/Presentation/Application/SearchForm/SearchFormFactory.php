<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Presentation\Application\SearchForm;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use GuzzleHttp\Psr7\Uri;
use Hoerbiger\HoerbigerCom\Domain\Stuff\SearchPeriod;
use Hoerbiger\HoerbigerCom\Domain\Stuff\SortingProperty;
use Neos\ContentRepository\Domain\Service\Context as ContentContext;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\I18n\Service as LocalizationService;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use Sitegeist\CsvPO\Domain\TranslationLabel;
use Sitegeist\CsvPO\Domain\TranslationLabelSourceRepository;
use Neos\Flow\Security\Context as SecurityContext;

final class SearchFormFactory extends AbstractComponentPresentationObjectFactory
{
    /**
     * @var TranslationLabelSourceRepository
     * @Flow\Inject
     */
    protected $translationLabelSourceRepository;

    /**
     * @Flow\Inject
     * @var LocalizationService
     */
    protected $localizationService;

    /**
     * @Flow\Inject
     * @var SecurityContext
     */
    protected $securityContext;

    public function create(ContentContext $subgraph): SearchForm
    {
        $uriBuilder = clone $this->uriService->getControllerContext()->getUriBuilder();
        $uriBuilder->setFormat('json');
        $endpointUri = new Uri(
            $uriBuilder
                ->setCreateAbsoluteUri(true)
                ->uriFor(
                    'query',
                    [],
                    'Stuff',
                    'Hoerbiger.HoerbigerCom',
                    'Application'
                )
        );
        $mostWantedEndpointUri = new Uri(
            $uriBuilder
                ->setCreateAbsoluteUri(true)
                ->uriFor(
                    'register',
                    [],
                    'WantedNode',
                    'Hoerbiger.HoerbigerCom',
                    'Application'
                )
        );

        $labelSource = $this->translationLabelSourceRepository->findOneByIdentifier(
            'resource://Hoerbiger.HoerbigerCom/Private/Fusion/Presentation/Application/SearchForm/SearchForm.translation.csv'
        );
        $labels = array_map(function (TranslationLabel $label): string {
            return $label->findTranslationForLocaleChain($this->localizationService->getLocaleChain(
                $this->localizationService->getConfiguration()->getCurrentLocale()
            ))?->getTranslation() ?: $label->getIdentifier();
        }, $labelSource ? $labelSource->findAllTranslationLabels() : []);

        $languageChain = $subgraph->getDimensions()['language'];

        return new SearchForm(
            $this->securityContext->getCsrfProtectionToken(),
            $endpointUri,
            json_encode(SortingProperty::cases()) ?: '[]',
            json_encode(SearchPeriod::cases()) ?: '[]',
            json_encode($labels) ?: '{}',
            reset($languageChain),
            $mostWantedEndpointUri
        );
    }
}
