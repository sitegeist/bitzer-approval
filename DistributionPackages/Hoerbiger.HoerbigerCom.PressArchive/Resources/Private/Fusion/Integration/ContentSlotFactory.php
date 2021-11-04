<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\PressArchive\Integration;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.PressArchive package.
 */

use Hoerbiger\Shared\Integration\CacheSegment\CacheSegment;
use Hoerbiger\Shared\Presentation\Block\Card\ArticleCard\ArticleCard;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\ImageSourceFactory;
use Hoerbiger\Shared\Presentation\Block\Link\Link;
use Hoerbiger\Shared\Presentation\Block\Link\LinkTarget;
use Hoerbiger\Shared\Presentation\Block\Link\LinkVariant;
use Hoerbiger\Shared\Presentation\Layout\Box\Box;
use Hoerbiger\Shared\Presentation\Layout\Box\BoxVariant;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainer;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainerVariant;
use Hoerbiger\Shared\Presentation\Layout\Grid\Grid;
use Hoerbiger\Shared\Presentation\Layout\Grid\GridVariant;
use Neos\ContentRepository\Domain\NodeAggregate\NodeName;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;

final class ContentSlotFactory extends AbstractComponentPresentationObjectFactory
{
    private ImageSourceFactory $imageSourceFactory;

    private FigureFactory $figureFactory;

    public function __construct(
        ImageSourceFactory $imageSourceFactory,
        FigureFactory $figureFactory
    ) {
        $this->imageSourceFactory = $imageSourceFactory;
        $this->figureFactory = $figureFactory;
    }

    public function forContentNode(TraversableNodeInterface $contentNode, bool $inBackend, TraversableNodeInterface $site): SlotInterface
    {
        switch ((string)$contentNode->getNodeTypeName()) {
            case 'Hoerbiger.HoerbigerCom.PressArchive:Content.LatestPressReleases':
                return $this->forLatestPressReleases($site, $inBackend);
            default:
                throw new \InvalidArgumentException('Don\'t know how to render nodes of type ' . $contentNode->getNodeTypeName());
        }
    }

    private function forLatestPressReleases(TraversableNodeInterface $site, bool $inBackend): SlotInterface
    {
        $pressArchive = $site->findNamedChildNode(NodeName::fromString('pressarchive'));
        $latestPressReleases = $pressArchive->findChildNodes(null, 3);

        return new ContentContainer(
            ContentContainerVariant::regular(),
            new CacheSegment(
                new Box(
                    BoxVariant::decorative(),
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
                                return new ArticleCard(
                                    $imageSource
                                        ? $this->figureFactory->forArticleCard($imageSource)
                                        : null,
                                    Value::fromString(
                                        $pressRelease->getProperty('preview__headline')
                                            ?: $pressRelease->getProperty('headline') ?: ''
                                    ),
                                    Value::fromString($pressRelease->getProperty('alternativeHeadline') ?: ''),
                                    Value::fromString(
                                        $pressRelease->getProperty('preview__description')
                                            ?: $pressRelease->getProperty('abstract') ?: ''
                                    ),
                                    new Link(
                                        LinkVariant::label(),
                                        $this->uriService->getNodeUri($pressRelease),
                                        LinkTarget::self(),
                                        null,
                                        Value::fromString($this->getLabel('list.learnMore'))
                                    ),
                                );
                            }
                        )
                    ),
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
