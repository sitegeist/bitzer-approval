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

final class FigureFactory extends AbstractComponentPresentationObjectFactory
{
    public function forImageMixin(
        TraversableNodeInterface $node,
        bool $inBackend,
        bool $hasFallbackInBackend = true
    ): ?Figure {
        $imageSource = null;

        if ($node->getProperty('image')) {
            $imageSource = new AssetImageSourceHelper($node->getProperty('image'));
            $imageSource->setAlt($node->getProperty('image__alt'));
            $imageSource->setTitle($node->getProperty('image__title'));
        } elseif ($inBackend && $hasFallbackInBackend) {
            $imageSource = new DummyImageSourceHelper((string)$this->uriService->getDummyImageBaseUri());
            $imageSource = $this->adjustDummyImageSource($imageSource, $node->getNodeType());
        }

        return $imageSource
            ? new Figure(
                $imageSource,
                true,
                null,
                null
            )
            : null;
    }

    public function forPreviewImageMixin(TraversableNodeInterface $targetDocument, bool $inBackend): ?Figure
    {
        $imageSource = null;

        if ($targetDocument->getProperty('preview__image')) {
            $imageSource = new AssetImageSourceHelper($targetDocument->getProperty('preview__image'));
            $imageSource->setAlt($targetDocument->getProperty('preview__image__alt') ?? '');
            $imageSource->setTitle($targetDocument->getProperty('preview__image__title') ?? '');

            return new Figure(
                $imageSource,
                true,
                FigureObjectFit::cover(),
                FigureObjectPosition::center()
            );
        }

        $figure = $this->forImageMixin($targetDocument, $inBackend, true);

        return $figure
            ? $figure->withObjectFitAndPosition(FigureObjectFit::cover(), FigureObjectPosition::center())
            : null;
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
