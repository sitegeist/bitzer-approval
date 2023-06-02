<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\ContentContainer;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Eel\ProtectedContextAwareInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\StringComponentVariant;

enum ContentContainerVariant:string implements ProtectedContextAwareInterface
{
    use StringComponentVariant;

    case VARIANT_REGULAR = 'regular';
    case VARIANT_REGULARNEUTRAL = 'regularNeutral';
    case VARIANT_SNUG = 'snug';
    case VARIANT_SNUGOVERLAY = 'snugOverlay';
    case VARIANT_TIGHT = 'tight';
    case VARIANT_REGULARSM = 'regularSm';
    case VARIANT_REGULARX = 'regularX';
    case VARIANT_BLANK = 'blank';
    case VARIANT_BLANKY = 'blankY';
    case VARIANT_BLANK_BOTTOM = 'blankBottom';
    case VARIANT_STAGEAFTERBREADCRUMB = 'stageAfterBreadcrumb';
    case VARIANT_REGULARSTAGEAFTERBREADCRUMB = 'regularStageAfterBreadcrumb';
    case VARIANT_STACKSTAGE = 'stackStage';
    case VARIANT_REGULAR_WITHOUT_BOTTOM = 'regularWithoutBottom';
    case VARIANT_HALF_WITH = 'halfWidth';
    case VARIANT_PRESS = 'press';
    case VARIANT_PRESS_SMALL = 'pressSmall';
    case VARIANT_PRESS_LEFT = 'pressLeft';
    case VARIANT_PRESS_LEFT_BLANK = 'pressLeftBlank';
    case VARIANT_Y_CENTERED = 'yCentered';
    case VARIANT_PRESS_IMAGE = 'pressImage';
    case VARIANT_VISIBLE_SM = 'visibleSm';
    case VARIANT_VISIBLE_MD = 'visibleMd';
    case VARIANT_VISIBLE_LG = 'visibleLg';
    case VARIANT_FULL_VIEW_HEIGHT = 'fullViewHeight';
    case VARIANT_SMALL_TEXT = 'smallText';
}
