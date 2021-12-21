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
use Hoerbiger\Shared\Presentation\Block\Link\LinkFactory;
use Hoerbiger\Shared\Presentation\Block\Card\CardInterface;
use Hoerbiger\Shared\Presentation\Block\Card\SearchResultCard\SearchResultCard;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Neos\Domain\Service\ContentContext;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
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
            'Hoerbiger.HoerbigerCom.Catalog:Document.IndustrySolution'
        ) as $industrySolution) {
            $imageSource = $this->imageSourceFactory->forPreviewMixin($industrySolutions, false);
            $link = (new LinkFactory())->forLinkToDocument($industrySolution);

            $cards[] = new NavigationCard(
                $imageSource ? $this->figureFactory->forNavigationCard($imageSource) : null,
                Value::fromString($industrySolution->getLabel()),
                null,
                Value::fromString($industrySolution->getProperty('abstract') ?: ''),
                $link
            );
        }

        return $cards;
    }

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

    public function forThing(TraversableNodeInterface $thing, string $typeIdentifier): CardInterface
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

        return new SearchResultCard(
            null,
            Value::fromString($thing->getLabel()),
            Value::fromString($translatedLabel ?: $typeIdentifier),
            $thing->getProperty('preview__description')
                ? Value::fromString($thing->getProperty('preview__description'))
                : null,
            null
        );
    }
}
