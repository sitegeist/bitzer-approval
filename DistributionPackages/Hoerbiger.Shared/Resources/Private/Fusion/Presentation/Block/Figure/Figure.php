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

    private bool $isToBeLoadedLazily;

    private ?FigureObjectFit $objectFit;

    private ?FigureObjectPosition $objectPosition;

    public function __construct(
        ImageSourceHelperInterface $imageSource,
        bool $isToBeLoadedLazily,
        ?FigureObjectFit $objectFit,
        ?FigureObjectPosition $objectPosition
    ) {
        $this->imageSource = $imageSource;
        $this->isToBeLoadedLazily = $isToBeLoadedLazily;
        $this->objectFit = $objectFit;
        $this->objectPosition = $objectPosition;
    }

    public function getImageSource(): ImageSourceHelperInterface
    {
        return $this->imageSource;
    }

    public function getIsToBeLoadedLazily(): bool
    {
        return $this->isToBeLoadedLazily;
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
