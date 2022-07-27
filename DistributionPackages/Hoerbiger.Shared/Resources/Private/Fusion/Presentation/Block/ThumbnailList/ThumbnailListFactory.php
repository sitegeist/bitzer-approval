<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\ThumbnailList;

/*
 * This file is part of the Hoerbiger.Shared package.
 */


use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineColor;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineSize;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineType;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Editable;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

final class ThumbnailListFactory extends AbstractComponentPresentationObjectFactory
{
    public function forHomepage(TraversableNodeInterface $node, SlotInterface $content, bool $inBackend): ThumbnailList
    {
        return new ThumbnailList(
            new Headline(
                HeadlineSize::large(),
                HeadlineColor::brand(),
                HeadlineType::h2(),
                Editable::fromNodeProperty($node, 'thumbs_headline')
            ),
            $content
        );
    }
}
