<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\PressArchive\Integration;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.PressArchive package.
 */

use Hoerbiger\Shared\Domain\FormattedDateFactory;
use Hoerbiger\Shared\Integration\CacheSegment\CacheSegment;
use Hoerbiger\Shared\Presentation\Block\Card\ArticleCard\ArticleCard;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\ImageSourceFactory;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineColor;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineFactory;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineSize;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineType;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineVariant;
use Hoerbiger\Shared\Presentation\Block\Link\Link;
use Hoerbiger\Shared\Presentation\Block\Link\LinkTarget;
use Hoerbiger\Shared\Presentation\Block\Link\LinkVariant;
use Hoerbiger\Shared\Presentation\Block\Text\Text;
use Hoerbiger\Shared\Presentation\Block\Text\TextVariant;
use Hoerbiger\Shared\Presentation\Layout\Box\Box;
use Hoerbiger\Shared\Presentation\Layout\Box\BoxVariant;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainer;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainerVariant;
use Hoerbiger\Shared\Presentation\Layout\Grid\Grid;
use Hoerbiger\Shared\Presentation\Layout\Grid\GridVariant;
use Hoerbiger\Shared\Presentation\Layout\Row\Row;
use Hoerbiger\Shared\Presentation\Layout\Row\RowVariant;
use Hoerbiger\Shared\Presentation\Layout\Stack\Stack;
use Hoerbiger\Shared\Presentation\Layout\Stack\StackVariant;
use Neos\ContentRepository\Domain\Model\Node;
use Neos\ContentRepository\Domain\NodeAggregate\NodeName;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
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

    public function __construct(
        ImageSourceFactory $imageSourceFactory,
        FigureFactory $figureFactory,
        FormattedDateFactory $formattedDateFactory
    ) {
        $this->imageSourceFactory = $imageSourceFactory;
        $this->figureFactory = $figureFactory;
        $this->formattedDateFactory = $formattedDateFactory;
    }

    public function forContentNode(TraversableNodeInterface $contentNode, bool $inBackend, TraversableNodeInterface $site): SlotInterface
    {
        switch ((string)$contentNode->getNodeTypeName()) {
            case 'Hoerbiger.HoerbigerCom.PressArchive:Content.LatestPressReleases':
                return $this->forLatestPressReleases($site, $contentNode, $inBackend);
            default:
                throw new \InvalidArgumentException('Don\'t know how to render nodes of type ' . $contentNode->getNodeTypeName());
        }
    }

    private function forLatestPressReleases(TraversableNodeInterface $site, TraversableNodeInterface $latestPressReleaseNode, bool $inBackend): SlotInterface
    {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        $pressArchive = $site->findNamedChildNode(NodeName::fromString('pressarchive'));
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
