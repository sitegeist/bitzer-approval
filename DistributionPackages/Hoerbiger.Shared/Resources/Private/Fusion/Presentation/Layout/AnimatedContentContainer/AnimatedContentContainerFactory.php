<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\AnimatedContentContainer;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;

final class AnimatedContentContainerFactory extends AbstractComponentPresentationObjectFactory
{
    public static function getClassForVisibleContainer(AnimatedContentContainerVariant $variant): string
    {
        $response = "";

        switch ($variant) {
            case AnimatedContentContainerVariant::VARIANT_FADE_IN:
                $response = "animate-fade-in-text";
                break;
            case AnimatedContentContainerVariant::VARIANT_FADE_AND_TRANSLATE:
                $response = "animate-fade-in-text-translate";
                break;
        }

        return $response;
    }

    public static function getClassForInvisibleContainer(AnimatedContentContainerVariant $variant): string
    {
        $response = "";

        switch ($variant) {
            case AnimatedContentContainerVariant::VARIANT_FADE_IN:
                $response = "animate-fade-out-text";
                break;
            case AnimatedContentContainerVariant::VARIANT_FADE_AND_TRANSLATE:
                $response = "animate-fade-out-text-translate";
                break;
        }

        return $response;
    }
}
