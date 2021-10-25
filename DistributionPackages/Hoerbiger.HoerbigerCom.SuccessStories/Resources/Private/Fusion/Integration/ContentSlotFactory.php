<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\SuccessStories\Integration;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.SuccessStories package.
 */

use Hoerbiger\Shared\Integration\CacheSegment\CacheSegment;
use Hoerbiger\Shared\Presentation\Block\Card\ArticleCard\ArticleCard;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\ImageSourceFactory;
use Hoerbiger\Shared\Presentation\Block\Link\Link;
use Hoerbiger\Shared\Presentation\Block\Link\LinkTarget;
use Hoerbiger\Shared\Presentation\Block\Link\LinkVariant;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainer;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainerVariant;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Neos\Domain\Service\ContentContext;
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

    public function forContentNode(TraversableNodeInterface $contentNode, bool $inBackend): SlotInterface
    {
        switch ((string)$contentNode->getNodeTypeName()) {
            case 'Hoerbiger.HoerbigerCom.SuccessStories:Content.TopSuccessStories':
                return $this->forTopSuccessStories($contentNode, $inBackend);
            default:
                throw new \InvalidArgumentException('Don\'t know how to render nodes of type ' . $contentNode->getNodeTypeName());
        }
    }

    private function forTopSuccessStories(TraversableNodeInterface $topSuccessStories, bool $inBackend): SlotInterface
    {
        return new ContentContainer(
            ContentContainerVariant::regularSm(),
            new CacheSegment(
                Collection::fromIterable(
                    $topSuccessStories->getProperty('successStories') ?: [],
                    function (TraversableNodeInterface $successStory) use ($inBackend): ArticleCard {
                        $imageSource = $this->imageSourceFactory->forPreviewMixin(
                            $successStory,
                            $inBackend,
                            false
                        );
                        return new ArticleCard(
                            $imageSource
                                ? $this->figureFactory->forArticleCard($imageSource)
                                : null,
                            Value::fromString(
                                $successStory->getProperty('preview__headline')
                                    ?: $successStory->getProperty('headline') ?: ''
                            ),
                            Value::fromString($successStory->getProperty('alternativeHeadline') ?: ''),
                            Value::fromString(
                                $successStory->getProperty('preview__description')
                                    ?: $successStory->getProperty('abstract') ?: ''
                            ),
                            new Link(
                                LinkVariant::label(),
                                $this->uriService->getNodeUri($successStory),
                                LinkTarget::self(),
                                null,
                                Value::fromString($this->getLabel('list.learnMore'))
                            ),
                        );
                    }
                ),
                'Hoerbiger.HoerbigerCom.SuccessStories:CacheSegment.SuccessStoryList'
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
            'SuccessStories',
            'Hoerbiger.HoerbigerCom.SuccessStories'
        ) ?: $identifier;
    }
}
