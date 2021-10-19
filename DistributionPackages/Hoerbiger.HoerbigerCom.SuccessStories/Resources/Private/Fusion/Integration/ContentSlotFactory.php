<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\SuccessStories\Integration;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.SuccessStories package.
 */

use Hoerbiger\Shared\Integration\CacheSegment\CacheSegment;
use Hoerbiger\Shared\Presentation\Block\Button\Button;
use Hoerbiger\Shared\Presentation\Block\Button\ButtonType;
use Hoerbiger\Shared\Presentation\Block\Button\ButtonVariant;
use Hoerbiger\Shared\Presentation\Block\Card\ArticleCard\ArticleCard;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Hoerbiger\Shared\Presentation\Block\Link\Link;
use Hoerbiger\Shared\Presentation\Block\Link\LinkTarget;
use Hoerbiger\Shared\Presentation\Block\Link\LinkVariant;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainer;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainerVariant;
use Neos\ContentRepository\Domain\NodeAggregate\NodeName;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Neos\Domain\Service\ContentContext;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;

final class ContentSlotFactory extends AbstractComponentPresentationObjectFactory
{
    public function forContentNode(TraversableNodeInterface $contentNode, ContentContext $subgraph, bool $inBackend, TraversableNodeInterface $site): SlotInterface
    {
        switch ((string)$contentNode->getNodeTypeName()) {
            case 'Hoerbiger.HoerbigerCom.SuccessStories:Content.TopSuccessStories':
                return $this->forTopSuccessStories($site, $inBackend);
            default:
                throw new \InvalidArgumentException('Don\'t know how to render nodes of type ' . $contentNode->getNodeTypeName());
        }
    }

    private function forTopSuccessStories(TraversableNodeInterface $site, bool $inBackend): SlotInterface
    {
        $magazine = $site->findNamedChildNode(NodeName::fromString('magazine'));
        $topSuccessStories = $magazine->findChildNodes(
            $this->nodeTypeConstraintFactory->parseFilterString('Hoerbiger.HoerbigerCom.SuccessStories:Document.SuccessStory'),
            3
        );

        $figureFactory = new FigureFactory();
        return new ContentContainer(
            ContentContainerVariant::regularSm(),
            new CacheSegment(
                Collection::fromIterable(
                    $topSuccessStories->toArray(),
                    function (TraversableNodeInterface $successStory) use ($figureFactory, $inBackend): ArticleCard {
                        $previewImage = $successStory->getProperty('preview__image') ?: $successStory->getProperty('image');
                        return new ArticleCard(
                            $previewImage
                                ? $figureFactory->forPreviewImageMixin($successStory, $inBackend)
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
