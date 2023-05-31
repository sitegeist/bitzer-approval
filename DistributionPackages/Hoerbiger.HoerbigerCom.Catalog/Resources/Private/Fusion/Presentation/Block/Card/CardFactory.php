<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Catalog\Presentation\Block\Card;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.Catalog package.
 */

use Hoerbiger\HoerbigerCom\Catalog\Domain\Catalog;
use Hoerbiger\HoerbigerCom\Presentation\Block\Card\CardFactoryInterface;
use Hoerbiger\Shared\Presentation\Block\Card\NavigationCard\NavigationCard;
use Hoerbiger\Shared\Presentation\Block\Card\ProductCard\ProductCard;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\ImageSourceFactory;
use Hoerbiger\Shared\Presentation\Block\Icon\Icon;
use Hoerbiger\Shared\Presentation\Block\Icon\IconSize;
use Hoerbiger\Shared\Presentation\Block\Link\LinkFactory;
use Hoerbiger\Shared\Presentation\Block\Card\CardInterface;
use Hoerbiger\Shared\Presentation\Block\Card\SearchResultCard\SearchResultCard;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineColor;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineSize;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineType;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineVariant;
use Hoerbiger\Shared\Presentation\Block\Tag\Tag;
use Hoerbiger\Shared\Presentation\Block\Tag\TagVariant;
use Hoerbiger\Shared\Presentation\Block\Text\Text;
use Hoerbiger\Shared\Presentation\Block\Text\TextVariant;
use Hoerbiger\Shared\Presentation\Layout\Grid\Grid;
use Hoerbiger\Shared\Presentation\Layout\Grid\GridVariant;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodes;
use Neos\Neos\Domain\Service\ContentContext;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Editable;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;
use Neos\Flow\I18n\Service as LocalizationService;
use Neos\Flow\Annotations as Flow;
use Sitegeist\CsvPO\Domain\TranslationLabelSourceRepository;

final class CardFactory extends AbstractComponentPresentationObjectFactory implements CardFactoryInterface
{
    /**
     * @Flow\Inject
     * @var Catalog
     */
    protected $catalog;

    /**
     * @Flow\Inject
     * @var ImageSourceFactory
     */
    protected $imageSourceFactory;

    /**
     * @Flow\Inject
     * @var FigureFactory
     */
    protected $figureFactory;

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
     * @return array<int,ProductCard>
     */
    public function forIndustrySolutionProducts(ContentContext $subgraph, TraversableNodeInterface $industrySolution): array
    {
        $cards = [];

        foreach ($this->catalog->findProductsForIndustrySolution($subgraph, $industrySolution) as $product) {
            $imageSource = $this->imageSourceFactory->forPreviewMixin($product, false);

            $cards[] = new ProductCard(
                $imageSource ? $this->figureFactory->forProductCard($imageSource) : null,
                Value::fromString($product->getLabel()),
                null,
                Value::fromString($product->getProperty('abstract') ?: ''),
                null
            );
        }

        return $cards;
    }

    /**
     * @return array<int,NavigationCard>
     */
    public function forIndustrySolutions(TraversableNodeInterface $industrySolutions): array
    {
        $cards = [];
        foreach ($this->findChildNodesByNodeTypeFilterString(
            $industrySolutions,
            'Hoerbiger.HoerbigerCom.Catalog:Document.IndustrySolution,Hoerbiger.HoerbigerCom:Document.Shortcut'
        ) as $industrySolution) {
            $imageSource = $this->imageSourceFactory->forPreviewMixin($industrySolution, false);
            $link = (new LinkFactory())->forLinkToDocument($industrySolution, null);

            $headline = new Headline(
                HeadlineSize::SIZE_SMALL,
                HeadlineColor::COLOR_BRAND,
                HeadlineType::TYPE_DIV,
                Value::fromString($industrySolution->getProperty('preview__headline') ?: $industrySolution->getLabel())
            );

            $text = new Text(
                TextVariant::VARIANT_LIGHT_CONTRAST,
                Value::fromString($industrySolution->getProperty('preview__description') ?: ''),
                false
            );

            $cards[] = new NavigationCard(
                $imageSource ? $this->figureFactory->forNavigationCard($imageSource) : null,
                $headline,
                null,
                $text,
                $link->withContent(
                    Value::fromString(
                        $this->translator->translateById('link.label', [], null, null, 'Block.Card.NavigationCard', 'Hoerbiger.HoerbigerCom') ?: ''
                    )
                )
            );
        }

        return $cards;
    }

    public function forThing(TraversableNodeInterface $thing, string $typeIdentifier, ?string $highlight): CardInterface
    {
        $labelSource = $this->translationLabelSourceRepository->findOneByIdentifier(
            'resource://Hoerbiger.HoerbigerCom/Private/Fusion/Presentation/Application/SearchForm/SearchForm.translation.csv'
        );
        $label = $labelSource
            ? $labelSource->findTranslationLabelByIdentifier('type.' . $typeIdentifier)
            : null;
        $translatedLabel = $label
            ? $label->findTranslationForLocaleChain($this->localizationService->getLocaleChain(
                $this->localizationService->getConfiguration()->getCurrentLocale()
            ))->getTranslation()
            : null;

        $supportingText = $highlight ?: $thing->getProperty('preview__description');

        $headline = new Headline(
            HeadlineSize::SIZE_TINY,
            HeadlineColor::COLOR_BRAND,
            HeadlineType::TYPE_H2,
            Value::fromString($thing->getLabel())
        );

        return new SearchResultCard(
            null,
            $headline,
            Value::fromString($translatedLabel ?: $typeIdentifier),
            $supportingText ? Value::fromString($supportingText) : null,
            null
        );
    }

    public function forGroupedProductsNode(TraversableNodeInterface $groupedProductListNode, bool $inBackend): SlotInterface
    {
        $imageSource = $this->imageSourceFactory->forImageMixin($groupedProductListNode, $inBackend);
        $figure = $imageSource
            ? $this->figureFactory->forProductCard($imageSource)
            : null;

        $products = Collection::fromNodes(
            TraversableNodes::fromArray($groupedProductListNode->getProperty('products') ?: []),
            function (TraversableNodeInterface $productDocument) {
                $link = (new LinkFactory())
                    ->forLinkToDocument($productDocument, null)
                    ->asPlainLabel();

                return $link->withContent(
                    new Grid(
                        GridVariant::iconRight(),
                        Collection::fromIterable([
                            new Tag(
                                'span',
                                TagVariant::VARIANT_BRANDCOLOR,
                                Value::fromString(
                                    $productDocument->getProperty('headline')
                                        ?: $productDocument->getLabel()
                                )
                            ),
                            new Icon('shared', 'arrowRight', IconSize::SIZE_SIZE_1_5)
                        ], function (SlotInterface $slot) {
                            return $slot;
                        })
                    )
                );
            }
        );

        return new ProductCard(
            $figure,
            Editable::fromNodeProperty($groupedProductListNode, 'name'),
            null,
            null,
            $products
        );
    }

    /**
     * @param array<int,TraversableNodeInterface> $products
     */
    public function forProductGroup(TraversableNodeInterface $productGroup, array $products, bool $inBackend) :SlotInterface
    {
        $imageSource = ($this->imageSourceFactory)->forImageMixin($productGroup, $inBackend);
        $figure = $imageSource
            ? ($this->figureFactory)->forProductCard($imageSource)
            : null;

        $products = Collection::fromNodes(
            TraversableNodes::fromArray($products),
            function (TraversableNodeInterface $productDocument) {
                $link = (new LinkFactory())
                    ->forLinkToDocument($productDocument, null)
                    ->asPlainLabel();

                return $link->withContent(
                    new Grid(
                        GridVariant::iconRight(),
                        Collection::fromIterable([
                            new Tag(
                                'span',
                                TagVariant::VARIANT_BRANDCOLOR,
                                Value::fromString(
                                    $productDocument->getProperty('headline')
                                        ?: $productDocument->getLabel()
                                )
                            ),
                            new Icon('shared', 'arrowRight', IconSize::SIZE_SIZE_1_5)
                        ], function (SlotInterface $slot) {
                            return $slot;
                        })
                    )
                );
            }
        );

        return new ProductCard(
            $figure,
            Value::fromString($productGroup->getLabel()),
            null,
            null,
            $products
        );
    }
}
