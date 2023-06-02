<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\EnumProvider\Theme;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

enum Theme:string
{
    case VALUE_EXCITE = 'excite';
    case VALUE_EXPERIENCE = 'experience';
    case VALUE_LITE = 'lite';
    case VALUE_SERVICE = 'service';
    case VALUE_DEFAULT = 'default';
    case VALUE_SLIDER = 'slider';
    case VALUE_SIMPLE_LOOP = 'simpleLoop';
}
