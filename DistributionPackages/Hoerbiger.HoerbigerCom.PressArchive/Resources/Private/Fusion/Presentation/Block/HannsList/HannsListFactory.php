<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\PressArchive\Presentation\Block\HannsList;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.PressArchive package.
 */

use GuzzleHttp\Psr7\Uri;
use Hoerbiger\HoerbigerCom\PressArchive\Presentation\Application\PressOverview\PressOverview;
use Hoerbiger\HoerbigerCom\PressArchive\Presentation\Application\PressOverview\PressOverviewVariant;
use Hoerbiger\HoerbigerCom\PressArchive\Presentation\EnumProvider\Press\PressVariant;
use Hoerbiger\Shared\Domain\FormattedDateFactory;
use Hoerbiger\Shared\Infrastructure\CsvPOTranslator;
use Hoerbiger\Shared\Presentation\Block\Button\Button;
use Hoerbiger\Shared\Presentation\Block\Button\ButtonType;
use Hoerbiger\Shared\Presentation\Block\Button\ButtonVariant;
use Hoerbiger\Shared\Presentation\Block\Icon\Icon;
use Hoerbiger\Shared\Presentation\Block\Icon\IconSize;
use Hoerbiger\Shared\Presentation\Block\Link\Link;
use Hoerbiger\Shared\Presentation\Block\Link\LinkFactory;
use Hoerbiger\Shared\Presentation\Block\Link\LinkTarget;
use Hoerbiger\Shared\Presentation\Block\Link\LinkVariant;
use Hoerbiger\Shared\Presentation\Layout\Grid\Grid;
use Hoerbiger\Shared\Presentation\Layout\Grid\GridVariant;
use Neos\ContentRepository\Domain\NodeAggregate\NodeName;
use Neos\Flow\Annotations as Flow;
use Hoerbiger\Shared\Presentation\Block\Card\PressReleaseCard\PressReleaseCard;
use Hoerbiger\Shared\Presentation\Block\Card\PressReleaseCard\PressReleaseCardVariant;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\ImageSourceFactory;
use Neos\ContentRepository\Domain\Model\Node;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Neos\Domain\Service\ContentContext;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;

final class HannsListFactory extends AbstractComponentPresentationObjectFactory
{
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
     * @Flow\Inject
     * @var CsvPOTranslator
     */
    protected $csvPOTranslator;

    /**
     * @Flow\Inject
     * @var FormattedDateFactory
     */
    protected $formattedDateFactory;

    /**
     * @Flow\Inject
     * @var LinkFactory
     */
    protected $linkFacotry;

    public function forHannsList(
        TraversableNodeInterface $overviewPage,
        ContentContext $subgraph,
        bool $inBackend
    ): SlotInterface
    {
        $pressReleases = [];

        $labels = $this->csvPOTranslator->getLabels(PressOverview::class);
        foreach ($overviewPage->findChildNodes() as $magazine) {
            $pressReleases = array_merge(
                $pressReleases,
                $this->findChildNodesByNodeTypeFilterString(
                    $magazine,
                    'Hoerbiger.HoerbigerCom:Document'
                )->toArray()
            );
        }
        /** @var array<int,Node> $pressReleases */

        uasort($pressReleases, function (Node $a, Node $b) {
            return ($b->getProperty('datePublished') ?: $b->getCreationDateTime())
                <=> ($a->getProperty('datePublished') ?: $a->getCreationDateTime());
        });

        $pressReleaseCards = array_map(
            function (Node $pressRelease) use ($inBackend, $labels): Link {
                $pressVariant = PressVariant::tryFromNode($pressRelease);

                $imageSource = $this->imageSourceFactory->forPreviewMixin($pressRelease, $inBackend);

                $showImage = $pressVariant !== PressVariant::VARIANT_NEWS || $pressRelease->getProperty('showImage');

                $figure = $imageSource && $showImage
                    ? $this->figureFactory->forPressReleaseCard($imageSource)
                    : null;

                $datePublished = $this->formattedDateFactory->createFullDate(
                    $pressRelease->getProperty('datePublished') ?: $pressRelease->getCreationDateTime()
                );
                return new Link(
                    LinkVariant::VARIANT_CONTAINER,
                    $this->uriService->getNodeUri($pressRelease),
                    LinkTarget::TARGET_SELF,
                    null,
                    new PressReleaseCard(
                        PressReleaseCardVariant::from(($pressVariant ?: PressVariant::VARIANT_NEWS)->value),
                        $pressVariant === PressVariant::VARIANT_NEWS
                            ? Value::fromString($datePublished ?: '')
                            : null,
                        $figure,
                        Value::fromString(
                            $pressRelease->getProperty('preview__headline')
                                ?: $pressRelease->getProperty('headline')
                                ?: ''
                        ),
                        Value::fromString(
                            ($labels['label.filter.' . $pressVariant?->value] ?? '')
                            . ' ' . $pressRelease->getProperty('edition')
                        ),
                        Value::fromString(
                            $pressRelease->getProperty('preview__description')
                                ?: $pressRelease->getProperty('abstract')
                                ?: ''
                        ),
                        new Button(
                            ButtonType::TYPE_REGULAR,
                            ButtonVariant::VARIANT_CONTRAST,
                            new Grid(
                                GridVariant::VARIANT_ICONLEFT,
                                Collection::fromIterable(array_filter([
                                    new Icon('shared', 'arrowRight', IconSize::SIZE_SIZE_1_5),
                                    Value::fromString($this->getLabel('list.learnMore'))
                                ]))
                            ),
                            null,
                            null,
                            null
                        )
                    ),
                    null
                );
            },
            $pressReleases
        );

        $hannsMagazine = $overviewPage->findNamedChildNode(NodeName::fromString('hanns'));

        $hannsMetaCardImageSource = $this->imageSourceFactory->forPreviewMixin(
            $hannsMagazine,
            $inBackend
        );

        $hansMetaCardFigure = $hannsMetaCardImageSource
            ? $this->figureFactory->forPressReleaseCard($hannsMetaCardImageSource)
            : null;

        $hansMetaCard = new Link(
            LinkVariant::VARIANT_CONTAINER,
            $this->linkFacotry->resolveRawLinkTargetUri(
                $hannsMagazine->getProperty('link__target'),
                $subgraph
            ) ?: new Uri('#'),
            LinkTarget::TARGET_SELF,
            null,
            new PressReleaseCard(
                PressReleaseCardVariant::VARIANT_META,
                null,
                $hansMetaCardFigure,
                Value::fromString($hannsMagazine->getProperty('preview__headline') ?: ''),
                null,
                Value::fromString($hannsMagazine->getProperty('preview__description') ?: ''),
                new Button(
                    ButtonType::TYPE_REGULAR,
                    ButtonVariant::VARIANT_CONTRAST,
                    new Grid(
                        GridVariant::VARIANT_ICONLEFT,
                        Collection::fromIterable(array_filter([
                            new Icon('shared', 'arrowRight', IconSize::SIZE_SIZE_1_5),
                            "DEBUG DUMMY"
                        ]))
                    ),
                    null,
                    null,
                    null
                )
            ),
            null
        );

        if ($hannsMagazine->getProperty('preview__headline') && $hannsMagazine->getProperty('preview__description')) {
            array_splice($pressReleaseCards, 2, 0 , '');
            $pressReleaseCards[2] = $hansMetaCard;
        }

        return new PressOverview(
            PressOverviewVariant::VARIANT_REGULAR,
            $labels,
            Collection::fromIterable(
                $pressReleaseCards
            )
        );
    }
    private function getLabel(string $identifier): string
    {
        return $this->csvPOTranslator->getLabelFromTranslationResource(
            'Hoerbiger.HoerbigerCom.PressArchive',
            'PressReleases',
            $identifier
        );
    }
}
