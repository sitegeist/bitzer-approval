<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Presentation\EnumProvider\Introduction;

/*
 * This file is part of the MeisterWerke.MeisterwerkeCom package.
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
enum StageVariant: string
{
    case VARIANT_FULLMEDIATEXTLEFT = 'fullMediaTextLeft';
    case VARIANT_FULLMEDIATEXTRIGHT = 'fullMediaTextRight';

}
