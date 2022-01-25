<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Catalog\Presentation\Layout\Stage;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.Catalog package.
 */

use GuzzleHttp\Psr7\Uri;
use Hoerbiger\HoerbigerCom\Catalog\Domain\ProductClass;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\ImageSourceFactory;
use Hoerbiger\Shared\Presentation\Block\SvgImage\SvgImage;
use Hoerbiger\Shared\Presentation\Layout\Stage\BrandStage\BrandStage;
use Hoerbiger\Shared\Presentation\Layout\Stage\StageVariant;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;

final class StageFactory extends AbstractComponentPresentationObjectFactory
{
    /**
     * @Flow\Inject
     * @var ImageSourceFactory
     */
    protected $imageSourceFactory;

    /**
     * @Flow\Inject
     * @var FigureFactory
     */
    protected $figureFactory;

    public function forProductAndCategoryIntroduction(TraversableNodeInterface $documentNode, bool $inBackend): BrandStage
    {
        $productClass = $documentNode->getProperty('productClass')
            ? ProductClass::from($documentNode->getProperty('productClass'))
            : null;
        $theme = $productClass ? $productClass->toTheme() : null;
        $media = $productClass
            ? new SvgImage(
                $this->resolveProductClassImageUri($productClass),
                $productClass->getLabel(),
                $productClass->getLabel()
            )
            : null;

        $backgroundImageSource = $this->imageSourceFactory->forImageMixin($documentNode, $inBackend);
        if (!$backgroundImageSource) {
            throw new \InvalidArgumentException(
                'Product / category introductions must have background images in frontend mode.'
            );
        }
        $background = $this->figureFactory->forFullMediaStage($backgroundImageSource);

        return new BrandStage(
            StageVariant::fromTheme($theme),
            null,
            null,
            null,
            null,
            $media,
            $background
        );
    }

    private function resolveProductClassImageUri(ProductClass $productClass): Uri
    {
        return $this->uriService->getResourceUri(
            'Hoerbiger.HoerbigerCom.Catalog',
            'Images/Compression/' . $productClass->getImageFileName()
        );
    }
}
