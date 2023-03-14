<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\PressArchive\Integration;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.PressArchive package.
 */

use Neos\Flow\Annotations as Flow;
use Hoerbiger\HoerbigerCom\PressArchive\Presentation\Application\PressOverview\PressOverview;
use Hoerbiger\HoerbigerCom\PressArchive\Presentation\Application\PressOverview\PressOverviewVariant;
use Hoerbiger\HoerbigerCom\PressArchive\Presentation\EnumProvider\Press\PressVariant;
use Hoerbiger\Shared\Domain\FormattedDateFactory;
use Hoerbiger\Shared\Infrastructure\CsvPOTranslator;
use Hoerbiger\Shared\Integration\CacheSegment\CacheSegment;
use Hoerbiger\Shared\Presentation\Block\Button\Button;
use Hoerbiger\Shared\Presentation\Block\Button\ButtonType;
use Hoerbiger\Shared\Presentation\Block\Button\ButtonVariant;
use Hoerbiger\Shared\Presentation\Block\Card\ArticleCard\ArticleCard;
use Hoerbiger\Shared\Presentation\Block\Card\PressReleaseCard\PressReleaseCard;
use Hoerbiger\Shared\Presentation\Block\Card\PressReleaseCard\PressReleaseCardVariant;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\ImageSourceFactory;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineColor;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineSize;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineType;
use Hoerbiger\Shared\Presentation\Block\Icon\Icon;
use Hoerbiger\Shared\Presentation\Block\Icon\IconSize;
use Hoerbiger\Shared\Presentation\Block\Link\Link;
use Hoerbiger\Shared\Presentation\Block\Link\LinkFactory;
use Hoerbiger\Shared\Presentation\Block\Link\LinkTarget;
use Hoerbiger\Shared\Presentation\Block\Link\LinkVariant;
use Hoerbiger\Shared\Presentation\Block\Separator\Separator;
use Hoerbiger\Shared\Presentation\Block\Separator\SeparatorVariant;
use Hoerbiger\Shared\Presentation\Block\Text\Text;
use Hoerbiger\Shared\Presentation\Block\Text\TextVariant;
use Hoerbiger\Shared\Presentation\Layout\Box\Box;
use Hoerbiger\Shared\Presentation\Layout\Box\BoxVariant;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainer;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainerVariant;
use Hoerbiger\Shared\Presentation\Layout\Grid\Grid;
use Hoerbiger\Shared\Presentation\Layout\Grid\GridVariant;
use Hoerbiger\Shared\Presentation\Layout\Stack\Stack;
use Hoerbiger\Shared\Presentation\Layout\Stack\StackVariant;
use Neos\ContentRepository\Domain\Model\Node;
use Neos\ContentRepository\Domain\NodeAggregate\NodeName;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodes;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Editable;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;
use Sitegeist\Kaleidoscope\EelHelpers\ResourceImageSourceHelper;

final class ContentSlotFactory extends AbstractComponentPresentationObjectFactory
{
    private ImageSourceFactory $imageSourceFactory;

    private FigureFactory $figureFactory;

    private FormattedDateFactory $formattedDateFactory;

    private LinkFactory $linkFactory;

    /**
     * @Flow\Inject
     * @var CsvPOTranslator
     */
    protected $csvPOTranslator;

    public function __construct(
        ImageSourceFactory $imageSourceFactory,
        FigureFactory $figureFactory,
        FormattedDateFactory $formattedDateFactory,
        LinkFactory $linkFactory
    ) {
        $this->imageSourceFactory = $imageSourceFactory;
        $this->figureFactory = $figureFactory;
        $this->formattedDateFactory = $formattedDateFactory;
        $this->linkFactory = $linkFactory;
    }

    public function forContentNode(
        TraversableNodeInterface $contentNode,
        bool $inBackend,
        TraversableNodeInterface $site
    ): SlotInterface {
        switch ((string)$contentNode->getNodeTypeName()) {
            case 'Hoerbiger.HoerbigerCom.PressArchive:Content.LatestPressReleases':
                return $this->forLatestPressReleases($site, $contentNode, $inBackend);
            case 'Hoerbiger.HoerbigerCom.PressArchive:Content.TopNews':
                return $this->forTopNewsNode($site, $contentNode, $inBackend);
            default:
                throw new \InvalidArgumentException('Don\'t know how to render nodes of type ' . $contentNode->getNodeTypeName());
        }
    }

    private function forLatestPressReleases(TraversableNodeInterface $site, TraversableNodeInterface $latestPressReleaseNode, bool $inBackend): SlotInterface
    {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        $pressArchive = $site->findNamedChildNode(
            NodeName::fromString('pressarchive')
        )->findNamedChildNode(
            NodeName::fromString('press')
        );

        $latestPressReleases = $pressArchive->findChildNodes(null, 3);

        return new ContentContainer(
            ContentContainerVariant::regular(),
            new CacheSegment(
                new Box(
                    BoxVariant::light(),
                    new Stack(
                        StackVariant::sm(),
                        Collection::fromIterable([
                            new Grid(
                                GridVariant::twoColumnsFrMaxGap(),
                                Collection::fromIterable([
                                    new Headline(
                                        HeadlineSize::large(),
                                        HeadlineColor::brand(),
                                        HeadlineType::h2(),
                                        Editable::fromNodeProperty($latestPressReleaseNode, 'headline')
                                    ),
                                    new Link(
                                        LinkVariant::label(),
                                        $this->uriService->getNodeUri($pressArchive),
                                        LinkTarget::self(),
                                        null,
                                        Value::fromString($this->getLabel('list.seeAll')),
                                        null
                                    ),
                                ], $identity)
                            ),
                            new Grid(
                                GridVariant::threeColumnsGap(),
                                Collection::fromIterable(
                                    $latestPressReleases,
                                    function (TraversableNodeInterface $pressRelease) use ($inBackend): ArticleCard {
                                        $imageSource = $this->imageSourceFactory->forPreviewMixin(
                                            $pressRelease,
                                            $inBackend,
                                            false
                                        );

                                        if (!$imageSource) {
                                            $imageSource = new ResourceImageSourceHelper('Hoerbiger.HoerbigerCom.PressArchive', 'Images/news_preview_fallback.png');
                                        }

                                        /** @var Node $pressRelease */
                                        /** @var TraversableNodeInterface $pressRelease */

                                        return new ArticleCard(
                                            new Link(
                                                LinkVariant::container(),
                                                $this->uriService->getNodeUri($pressRelease),
                                                LinkTarget::self(),
                                                null,
                                                $this->figureFactory->forArticleCard($imageSource),
                                                null
                                            ),
                                            Value::fromString(
                                                $pressRelease->getProperty('preview__headline')
                                                    ?: $pressRelease->getProperty('headline') ?: ''
                                            ),
                                            null,
                                            new Text(
                                                TextVariant::lightContrast(),
                                                Value::fromString(
                                                    $pressRelease->getProperty('preview__description')
                                                        ?: $pressRelease->getProperty('abstract') ?: ''
                                                ),
                                                false
                                            ),
                                            new Link(
                                                LinkVariant::label(),
                                                $this->uriService->getNodeUri($pressRelease),
                                                LinkTarget::self(),
                                                null,
                                                Value::fromString($this->getLabel('list.learnMore')),
                                                null
                                            )
                                        );
                                    }
                                )
                            ),
                        ], $identity)
                    )
                ),
                'Hoerbiger.HoerbigerCom.PressArchive:CacheSegment.PressReleaseList'
            )
        );
    }

    public function forTopNewsNode(
        TraversableNodeInterface $site,
        TraversableNodeInterface $topNewsNode,
        bool $inBackend
    ): SlotInterface
    {
        $overViewButton = new Button(
            ButtonType::link(),
            ButtonVariant::contrast(),
            new Grid(
                GridVariant::iconLeft(),
                Collection::fromIterable(array_filter([
                    new Icon('shared', 'arrowRight', IconSize::size_1_5()),
                    Value::fromString($this->getLabel('list.seeOverview') ?: '')
                ]))
            ),
            $this->uriService->getNodeUri(
                $site->findNamedChildNode(NodeName::fromString('pressarchive'))
            ),
            '_self',
            null
        );

        $labels = $this->csvPOTranslator->getLabels(PressOverview::class);

        $pressReleases = Collection::fromNodes(
            TraversableNodes::fromArray(
                $topNewsNode->getProperty('news') ? array_slice($topNewsNode->getProperty('news'), 0, 8, true)
                    : []
            ),
            function(Node $pressRelease) use($inBackend, $labels){
                $pressVariant = PressVariant::fromNode($pressRelease);

                $datePublished = $this->formattedDateFactory->createReducedYearDate(
                    $pressRelease->getProperty('datePublished') ?: $pressRelease->getCreationDateTime()
                );

                $imageSource = $this->imageSourceFactory->forPreviewMixin($pressRelease, $inBackend);

                $showImage = !$pressVariant->getIsNews() || $pressRelease->getProperty('showImage');

                $figure = $imageSource && $showImage
                    ? $this->figureFactory->forPressReleaseCard($imageSource)
                    : null;

                return $this->linkFactory->forLinkToDocument($pressRelease, null)
                    ->asPlainLabel()->withContent(
                        new PressReleaseCard(
                            $pressVariant->getIsNews()
                                ? PressReleaseCardVariant::top()
                                : PressReleaseCardVariant::topNews(),
                            $pressVariant->getIsNews()
                                ? Value::fromString($datePublished ?: '')
                                : null,
                            $figure,
                            Value::fromString(
                                $pressRelease->getProperty('preview__headline')
                                    ?: $pressRelease->getProperty('headline')
                                    ?: ''
                            ),
                            Value::fromString(
                                $labels['label.filter.' . $pressVariant->getValue()]
                                . ' ' . $pressRelease->getProperty('edition')
                            ),
                            Value::fromString(
                                $pressRelease->getProperty('preview__description')
                                    ?: $pressRelease->getProperty('abstract')
                                    ?: ''
                            ),
                            new Button(
                                ButtonType::regular(),
                                ButtonVariant::contrast(),
                                new Grid(
                                    GridVariant::iconLeft(),
                                    Collection::fromIterable(array_filter([
                                        new Icon('shared', 'arrowRight', IconSize::size_1()),
                                        new Text(
                                            TextVariant::sm(),
                                            Value::fromString($this->getLabel('list.learnMore')),
                                            false
                                        )
                                    ]))
                                ),
                                null,
                                null,
                                null
                            )
                        )
                    );
            }
        );

        return new ContentContainer(
            ContentContainerVariant::regular(),
            new Stack(
                StackVariant::sm(),
                Collection::fromIterable([
                    new Separator(
                        SeparatorVariant::fullWidthWithContent(),
                        Editable::fromNodeProperty($topNewsNode, 'metaHeadline')
                    ),
                    new Grid(
                        GridVariant::twoColumnsThirdAutoGap(),
                        Collection::fromIterable([
                            new Stack(
                                StackVariant::sm(),
                                Collection::fromIterable(array_filter([
                                    $inBackend || $topNewsNode->getProperty('headline')
                                        ? new Headline(
                                            HeadlineSize::superLargeLight(),
                                            HeadlineColor::text(),
                                            HeadlineType::h2(),
                                            Editable::fromNodeProperty($topNewsNode, 'headline')
                                        )
                                        : null,
                                        $overViewButton
                                    ])
                                )
                            ),
                            new PressOverview(
                                PressOverviewVariant::topNews(),
                                [],
                                $pressReleases
                            )
                        ])
                    )
                ])
            )
        );
    }

    /**
     * @param array<mixed,mixed> $arguments
     */
    private function getLabel(string $identifier, array $arguments = []): string
    {
        return $this->translator->translateById(
            $identifier,
            $arguments,
            null,
            null,
            'PressReleases',
            'Hoerbiger.HoerbigerCom.PressArchive'
        ) ?: $identifier;
    }
}
