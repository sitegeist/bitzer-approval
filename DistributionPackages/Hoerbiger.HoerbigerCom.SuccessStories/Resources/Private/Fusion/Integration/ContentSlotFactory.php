<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\SuccessStories\Integration;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.SuccessStories package.
 */

use Hoerbiger\Shared\Domain\FormattedDateFactory;
use Hoerbiger\Shared\Integration\CacheSegment\CacheSegment;
use Hoerbiger\Shared\Presentation\Block\Button\Button;
use Hoerbiger\Shared\Presentation\Block\Button\ButtonType;
use Hoerbiger\Shared\Presentation\Block\Button\ButtonVariant;
use Hoerbiger\Shared\Presentation\Block\Card\ArticleCard\ArticleCard;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\ImageSourceFactory;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineType;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineVariant;
use Hoerbiger\Shared\Presentation\Block\Link\Link;
use Hoerbiger\Shared\Presentation\Block\Link\LinkTarget;
use Hoerbiger\Shared\Presentation\Block\Link\LinkVariant;
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
            case 'Hoerbiger.HoerbigerCom.SuccessStories:Content.TopSuccessStories':
                return $this->forTopSuccessStories($site, $contentNode, $inBackend);
            default:
                throw new \InvalidArgumentException('Don\'t know how to render nodes of type ' . $contentNode->getNodeTypeName());
        }
    }

    private function forTopSuccessStories(TraversableNodeInterface $site, TraversableNodeInterface $topSuccessStories, bool $inBackend): SlotInterface
    {
        $identity = function(SlotInterface $slot) {
            return $slot;
        };

        $successStories = $site->findNamedChildNode(NodeName::fromString('magazine'));

        return new ContentContainer(
            ContentContainerVariant::regular(),
            new CacheSegment(
                new Box(
                    BoxVariant::decorative(),
                    new Stack(
                        StackVariant::sm(),
                        Collection::fromIterable([
                            new Grid(
                                GridVariant::twoColumnsFrMaxGap(),
                                Collection::fromIterable([
                                    new Headline(
                                        HeadlineVariant::regular(),
                                        HeadlineType::h2(),
                                        Editable::fromNodeProperty($topSuccessStories, 'headline')
                                    ),
                                    new Link(
                                        LinkVariant::label(),
                                        $this->uriService->getNodeUri($successStories),
                                        LinkTarget::self(),
                                        null,
                                        Value::fromString($this->getLabel('list.seeAll') ?: ''),
                                        null
                                    )
                                ], $identity)
                            ),
                            new Grid(
                                GridVariant::threeColumnsGap(),
                                Collection::fromIterable(
                                    $topSuccessStories->getProperty('successStories') ?: [],
                                    function (TraversableNodeInterface $successStory) use ($inBackend, $identity): ArticleCard {
                                        $imageSource = $this->imageSourceFactory->forPreviewMixin(
                                            $successStory,
                                            $inBackend,
                                            false
                                        );

                                        $applicationWorld = $successStory->getProperty('applicationWorld');

                                        /** @var Node $successStory */
                                        $datePublished = $successStory->getProperty('datePublished') ?: $successStory->getCreationDateTime();
                                        /** @var TraversableNodeInterface $successStory */


                                        return new ArticleCard(
                                            $imageSource
                                                ? new Link(
                                                    LinkVariant::container(),
                                                    $this->uriService->getNodeUri($successStory),
                                                    LinkTarget::self(),
                                                    null,
                                                    $this->figureFactory->forArticleCard($imageSource),
                                                    null
                                                )
                                                : null,
                                            Value::fromString(
                                                $successStory->getProperty('preview__headline')
                                                    ?: $successStory->getProperty('headline') ?: ''
                                            ),
                                            new Row(
                                                RowVariant::alternateMetalineEllipsis(),
                                                Collection::fromIterable([
                                                    Value::fromString($this->formattedDateFactory->createFullDate($datePublished)),
                                                    Value::fromString($applicationWorld->getProperty('previewHeadline')
                                                        ? $applicationWorld->getProperty('previewHeadline')
                                                        : $applicationWorld->getProperty('title') ?? ''
                                                    )
                                                ], $identity)
                                            ),
                                            Value::fromString(
                                                $successStory->getProperty('preview__description')
                                                    ?: $successStory->getProperty('abstract') ?: ''
                                            ),
                                            new Button(
                                                ButtonType::link(),
                                                ButtonVariant::cta(),
                                                Value::fromString($this->getLabel('list.learnMore') ?: ''),
                                                $this->uriService->getNodeUri($successStory),
                                                '_self',
                                                null
                                            )
                                        );
                                    }
                                )
                            )
                        ], $identity)
                    )
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
