<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Video;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\Block\Figure\FigureInterface;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;

final class VideoFactory extends AbstractComponentPresentationObjectFactory
{
    public function forVideoWithCoverImage(TraversableNodeInterface $node, string $videoPropertyName, ?FigureInterface $coverImage): Video
    {
        $asset = $node->getProperty($videoPropertyName);
        $assetUri = $asset ? $this->uriService->getAssetUri($asset) : '';
        return new Video(
            (string) $assetUri,
            $coverImage
        );
    }
}
