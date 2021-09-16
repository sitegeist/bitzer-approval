<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Figure;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Sitegeist\Kaleidoscope\EelHelpers\ImageSourceHelperInterface;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureObjectFit;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureObjectPosition;

/**
 * @Flow\Proxy(false)
 */
final class Figure extends AbstractComponentPresentationObject implements FigureInterface
{
    private ImageSourceHelperInterface $imageSource;

    private ?FigureObjectFit $objectFit;

    private ?FigureObjectPosition $objectPosition;

    public function __construct(
        ImageSourceHelperInterface $imageSource,
        ?FigureObjectFit $objectFit,
        ?FigureObjectPosition $objectPosition
    ) {
        $this->imageSource = $imageSource;
        $this->objectFit = $objectFit;
        $this->objectPosition = $objectPosition;
    }

    public function getImageSource(): ImageSourceHelperInterface
    {
        return $this->imageSource;
    }

    public function getObjectFit(): ?FigureObjectFit
    {
        return $this->objectFit;
    }

    public function getObjectPosition(): ?FigureObjectPosition
    {
        return $this->objectPosition;
    }
}
