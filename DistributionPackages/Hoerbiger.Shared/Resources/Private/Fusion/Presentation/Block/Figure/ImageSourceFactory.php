<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Figure;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\ContentRepository\Domain\Model\NodeType;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use Sitegeist\Kaleidoscope\EelHelpers\AssetImageSourceHelper;
use Sitegeist\Kaleidoscope\EelHelpers\DummyImageSourceHelper;
use Sitegeist\Kaleidoscope\EelHelpers\ImageSourceHelperInterface;

final class ImageSourceFactory extends AbstractComponentPresentationObjectFactory
{
    public function forImageMixin(
        TraversableNodeInterface $node,
        bool $inBackend,
        bool $hasFallbackInBackend = true
    ): ?ImageSourceHelperInterface {
        return $this->forImageProperty(
            $node, $inBackend,
            'image',
            $hasFallbackInBackend
        );
    }

    public function forImageProperty(
        TraversableNodeInterface $node,
        bool $inBackend,
        string $propertyPrefix,
        bool $hasFallbackInBackend = true
    ): ?ImageSourceHelperInterface {
        $imageSource = null;

        if ($node->getProperty($propertyPrefix)) {
            $imageSource = new AssetImageSourceHelper($node->getProperty($propertyPrefix));
            $imageSource->setAlt($node->getProperty($propertyPrefix.'__alt'));
            $imageSource->setTitle($node->getProperty($propertyPrefix.'__title'));
        } elseif ($inBackend && $hasFallbackInBackend) {
            $imageSource = new DummyImageSourceHelper((string)$this->uriService->getDummyImageBaseUri());
            $imageSource = $this->adjustDummyImageSource($imageSource, $node->getNodeType());
        }

        return $imageSource;
    }

    private function adjustDummyImageSource(
        DummyImageSourceHelper $imageSource,
        NodeType $nodeType
    ): DummyImageSourceHelper {
        switch ($nodeType->getConfiguration('properties.image.ui.inspector.editorOptions.crop.aspectRatio.locked')) {
            case ['width' => 1, 'height' => 1]:
                /** @var DummyImageSourceHelper $imageSource */
                $imageSource = $imageSource
                    ->setWidth(1080)
                    ->setHeight(1080);
                break;
            case ['width' => 3, 'height' => 1]:
                /** @var DummyImageSourceHelper $imageSource */
                $imageSource = $imageSource
                    ->setWidth(1920)
                    ->setHeight(640);
                break;
            case null:
            default:
                /** @var DummyImageSourceHelper $imageSource */
                $imageSource = $imageSource
                    ->setWidth(1920)
                    ->setHeight(1080);
        }

        return $imageSource;
    }
}
