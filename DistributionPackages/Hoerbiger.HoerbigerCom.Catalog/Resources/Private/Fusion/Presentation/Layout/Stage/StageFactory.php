<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Catalog\Presentation\Layout\Stage;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.Catalog package.
 */

use GuzzleHttp\Psr7\Uri;
use Hoerbiger\HoerbigerCom\Catalog\Domain\ProductClass;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\ImageSourceFactory;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineColor;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineSize;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineType;
use Hoerbiger\Shared\Presentation\Block\SvgImage\SvgImage;
use Hoerbiger\Shared\Presentation\Block\Text\Text;
use Hoerbiger\Shared\Presentation\Block\Text\TextVariant;
use Hoerbiger\Shared\Presentation\Layout\Stage\BrandStage\BrandStage;
use Hoerbiger\Shared\Presentation\Layout\Stage\StageVariant;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Editable;

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
    private TextVariant $textColor;

    public function forProductAndCategoryIntroduction(TraversableNodeInterface $documentNode, bool $inBackend): BrandStage
    {
        $productClass = $documentNode->getProperty('productClass')
            ? ProductClass::from($documentNode->getProperty('productClass'))
            : null;
        $theme = $productClass ? $productClass->toTheme() : null;
        $media = $productClass && !$productClass->getIsDefault()
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

        $this->textColor = TextVariant::tryFromTheme($theme, true) ?: TextVariant::lightContrast();

        if($theme && $theme->getValue() === 'default'){
            $this->textColor = TextVariant::brandContrast();
        }

        return new BrandStage(
            StageVariant::fromTheme($theme),
            null,
            new Headline(
                HeadlineSize::large(),
                HeadlineColor::white(),
                HeadlineType::h1(),
                Editable::fromNodeProperty($documentNode, 'headline')
            ),
            null,
            new Text(
                $this->textColor,
                Editable::fromNodeProperty($documentNode, 'abstract'),
                false
            ),
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
