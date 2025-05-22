<?php

declare(strict_types=1);

namespace Hoerbiger\Shared\Presentation\Block\SiteHeader;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use GuzzleHttp\Psr7\Uri;
use Hoerbiger\Shared\Domain\IsInsideHiddenParent;
use Hoerbiger\Shared\Presentation\Block\Button\Button;
use Hoerbiger\Shared\Presentation\Block\Button\ButtonType;
use Hoerbiger\Shared\Presentation\Block\Button\ButtonVariant;
use Hoerbiger\Shared\Presentation\Block\Icon\Icon;
use Hoerbiger\Shared\Presentation\Block\Icon\IconSize;
use Hoerbiger\Shared\Presentation\Block\Link\LinkFactory;
use Hoerbiger\Shared\Presentation\Block\NavigationItem\NavigationItem;
use Hoerbiger\Shared\Presentation\Block\NavigationItem\NavigationItems;
use Neos\ContentRepository\Domain\Model\Node;
use Neos\ContentRepository\Domain\NodeAggregate\NodeName;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Hoerbiger\Shared\Presentation\Layout\Grid\Grid;
use Hoerbiger\Shared\Presentation\Layout\Grid\GridVariant;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use Neos\Flow\I18n\Service as LocalizationService;
use Neos\Flow\Mvc\ActionRequest;
use Neos\Neos\Domain\Service\ContentContext;
use Neos\Neos\Exception;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use Neos\ContentRepository\Domain\Service\ContentDimensionPresetSourceInterface;
use Neos\Neos\Domain\Service\ContentContextFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;
use Sitegeist\CsvPO\Domain\TranslationLabel;
use Sitegeist\CsvPO\Domain\TranslationLabelSourceRepository;
use Neos\Flow\Annotations as Flow;

final class SiteHeaderFactory extends AbstractComponentPresentationObjectFactory
{
    private ContentDimensionPresetSourceInterface $contentDimensionPresetSource;

    private ContentContextFactory $contentContextFactory;

    private LinkFactory $linkFactory;

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

    public function __construct(
        ContentDimensionPresetSourceInterface $contentDimensionPresetSource,
        LinkFactory $linkFactory,
        ContentContextFactory $contentContextFactory
    ) {
        $this->contentDimensionPresetSource = $contentDimensionPresetSource;
        $this->linkFactory = $linkFactory;
        $this->contentContextFactory = $contentContextFactory;
    }

    public function forDocument(
        TraversableNodeInterface $document,
        TraversableNodeInterface $site,
        ContentContext $subgraph,
        bool $inBackend,
        ?ActionRequest $actionRequest = null,
    ): SiteHeader {
        $siteHeaderVariant = match ($document->getNodeType()->getName()) {
            'Hoerbiger.HoerbigerCom:Document.HomePage',
            'Hoerbiger.HoerbigerCom:Document.WebPage',
            'Hoerbiger.HoerbigerCom:Document.CareerPage',
            'Hoerbiger.HoerbigerCom:Document.CareerRootPage',
            'Hoerbiger.HoerbigerCom:Document.Chronicle',
            'Hoerbiger.HoerbigerCom:Document.ContactPage',
            'Hoerbiger.HoerbigerCom:Document.Error.404',
            'Hoerbiger.HoerbigerCom:Document.PrivacyPage',
            'Hoerbiger.HoerbigerCom:Document.SearchResultsPage',
            'Hoerbiger.HoerbigerCom.SuccessStories:Document.Magazine',
            'Hoerbiger.HoerbigerCom.HR:Document.JobList',
            'Hoerbiger.HoerbigerCom.Catalog:Document.Catalog',
            'Hoerbiger.HoerbigerCom.Catalog:Document.IndustrySolutions',
            'Hoerbiger.HoerbigerCom.Catalog:Document.IndustrySolution',
            'Hoerbiger.HoerbigerCom.AffiliateDirectory:Document.AffiliateFinder',
            'Hoerbiger.HoerbigerCom:Document.PeopleAndCultureWebPage' => SiteHeaderVariant::VARIANT_TRANSPARENT,
            default => SiteHeaderVariant::VARIANT_REGULAR,
        };

        $labelSource = $this->translationLabelSourceRepository->findOneByIdentifier(
            'resource://Hoerbiger.Shared/Private/Fusion/Presentation/Block/SiteHeader/SiteHeader.translation.csv'
        );
        $labels = array_map(function (TranslationLabel $label): string {
            return $label->findTranslationForLocaleChain($this->localizationService->getLocaleChain(
                $this->localizationService->getConfiguration()->getCurrentLocale()
            ))->getTranslation() ?: $label->getIdentifier();
        }, $labelSource ? $labelSource->findAllTranslationLabels() : []);

        $language = $subgraph->getDimensions()['language'][0];

        $exportButton = null;

        if ($inBackend && !in_array($language, ['de', 'en'])) {
            $uriBuilder = clone $this->uriService->getControllerContext()->getUriBuilder();
            $uriBuilder->setFormat('json');

            $exportButton = new Button(
                ButtonType::TYPE_LINK,
                ButtonVariant::VARIANT_NEOS,
                new Grid(
                    GridVariant::VARIANT_ICONLEFT,
                    Collection::fromIterable([
                        new Icon("shared", "translation", IconSize::SIZE_SIZE_1),
                        Value::fromString('Export Translations')
                    ])
                ),
                new Uri($uriBuilder->uriFor(
                    'exportDocument',
                    [
                        'documentIdentifier' => (string)$document->getNodeAggregateIdentifier(),
                        'language' => $language,
                    ],
                    'Translation',
                    'Hoerbiger.HoerbigerCom',
                    'Application'
                )),
                '_self',
                null
            );
        }

        return new SiteHeader(
            $siteHeaderVariant,
            $this->uriService->getNodeUri($site),
            $this->getChildNavigationNodes($site, $document, 1),
            $this->getLanguageNavigationItems($site, $document, $subgraph, $actionRequest),
            new Icon("shared", "naviChevron", IconSize::SIZE_SIZE_0_5),
            new Icon("shared", "arrowRight", IconSize::SIZE_SIZE_1),
            $this->uriService->getNodeUri($site->findNamedChildNode(NodeName::fromString('searchresults')), true),
            new Icon("shared", "search", IconSize::SIZE_SIZE_1_5),
            json_encode($labels) ?: '{}',
            $exportButton
        );
    }

    /**
     * @param TraversableNodeInterface $node
     * @param int $currentLevel
     * @param int $maxDepth
     * @return NavigationItems
     * @throws Exception
     * @throws \Neos\Flow\Http\Exception
     * @throws \Neos\Flow\Mvc\Routing\Exception\MissingActionNameException
     * @throws \Neos\Flow\Persistence\Exception\IllegalObjectTypeException
     * @throws \Neos\Flow\Property\Exception
     * @throws \Neos\Flow\Security\Exception
     */
    public function getChildNavigationNodes(
        TraversableNodeInterface $node,
        TraversableNodeInterface $currentDocumentNode,
        int $currentLevel,
        int $maxDepth = 3,
        int $maxItemsPerColumn = 5
    ): ?NavigationItems {
        $labelSource = $this->translationLabelSourceRepository->findOneByIdentifier(
            'resource://Hoerbiger.Shared/Private/Fusion/Presentation/Block/SiteHeader/SiteHeader.translation.csv'
        );

        $label = $labelSource?->findTranslationLabelByIdentifier('label_overview');

        $translatedLabel = $label?->findTranslationForLocaleChain(
            $this->localizationService->getLocaleChain(
                $this->localizationService->getConfiguration()->getCurrentLocale()
            )
        )->getTranslation();

        if ($currentLevel <= $maxDepth) {
            $menuItems = [];
            $childNodes = $this->findChildNodesByNodeTypeFilterString($node, 'Neos.Neos:Document,!Hoerbiger.Shared:Tag.ExcludeFromNavigation');

            foreach ($childNodes as $childNode) {
                /** @var Node $childNode*/
                if (!$childNode->isHiddenInIndex()) {
                    try {
                        $menuItems[] = new NavigationItem(
                            $this->linkFactory->forLinkToDocument($childNode, null)->asPlainLabel(),
                            $this->getChildNavigationNodes(
                                $childNode,
                                $currentDocumentNode,
                                $currentLevel + 1
                            ),
                            $this->isParentNodeOfCurrentDocument($childNode, $currentDocumentNode)
                        );
                    } catch (Exception $e) {
                    }
                }
            }

            if (count($menuItems)) {
                if ($currentLevel <= 1) {
                    return new NavigationItems($menuItems);
                } else {
                    $labelContent = $translatedLabel.' ' .$node->getLabel();
                    $parentNavItem[] = new NavigationItem(
                        $this->linkFactory->forLinkToDocumentNavigationOverview($node, $labelContent, null)->asPlainLabel(),
                        null,
                        $this->isParentNodeOfCurrentDocument($node, $currentDocumentNode)
                    );

                    $totalItems = count($menuItems);
                    $countColumns = ceil($totalItems / $maxItemsPerColumn);

                    $newMenuItems = [];

                    for ($i = 0; $i <= $countColumns - 1; $i++) {
                        $subItems = array_slice($menuItems, ($i * $maxItemsPerColumn), $maxItemsPerColumn);
                        if ($i === 0) {
                            $subItems = array_merge($parentNavItem, $subItems);
                        }

                        $newMenuItems[$i] = new NavigationItem(
                            $menuItems[$i]->link,
                            new NavigationItems($subItems),
                            true
                        );
                    };

                    return new NavigationItems($newMenuItems);
                }
            }
        }

        return null;
    }

    protected function isParentNodeOfCurrentDocument(TraversableNodeInterface $childDocument, TraversableNodeInterface $currentDocument): bool
    {
        $childNodePath = rtrim((string)$childDocument->findNodePath(), '/') . '/';
        return str_starts_with($currentDocument->findNodePath() . '/', $childNodePath);
    }

    protected function getLanguageNavigationItems(
        TraversableNodeInterface $site,
        TraversableNodeInterface $documentNode,
        ContentContext $subgraph,
        ?ActionRequest $request
    ): NavigationItems {
        /* Languages */
        $currentLanguageIdentifier = strtoupper(reset($subgraph->getDimensions()['language']));
        $languages = [];

        $queryString = $request ? $request->getHttpRequest()->getServerParams()['QUERY_STRING'] : null;

        foreach ($this->contentDimensionPresetSource->getAllPresets()['language']['presets'] as $localeIdentifier => $preset) {
            $contextProperties = $subgraph->getProperties();

            $contextProperties['dimensions'] = [
                'language' => $preset['values']
            ];

            $contextProperties['targetDimensions'] = [
                'language' => reset($preset['values'])
            ];

            /** @var ContentContext $contentContext */
            $contentContext = $this->contentContextFactory->create($contextProperties);

            /** @var TraversableNodeInterface|null $variant */
            $variant = $contentContext->getNodeByIdentifier((string)$documentNode->getNodeAggregateIdentifier())
                ?: null;

            if ($variant && !IsInsideHiddenParent::isSatisfiedByNode($contentContext, $site, $variant)) {
                $identifier = strtoupper($localeIdentifier);
                $language = [$this->uriService->getNodeUri($variant), $identifier];
                $languages[] = $language;
            }
        }
        $children = [];

        foreach ($languages as $language) {
            /** @var Uri $documentUri */
            $documentUri = $language[0] ;

            $children[] = new NavigationItem(
                $this->linkFactory->forLanguageMenuItem(
                    $queryString ? $documentUri->withQuery($queryString): $documentUri,
                    $language[1]
                ),
                null,
                $currentLanguageIdentifier === $language[1]
            );
        }

        return new NavigationItems($children);
    }
}
