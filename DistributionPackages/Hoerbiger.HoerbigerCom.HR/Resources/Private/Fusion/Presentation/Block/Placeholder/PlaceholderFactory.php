<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\HR\Presentation\Block\Placeholder;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.HR package.
 */

use Hoerbiger\HoerbigerCom\HR\Presentation\Block\Placeholder\ApplyPlaceholder\ApplyPlaceholder;
use Hoerbiger\HoerbigerCom\HR\Presentation\Block\Placeholder\IndexPlaceholder\IndexPlaceholder;
use Hoerbiger\HoerbigerCom\HR\Presentation\Block\Placeholder\RegularPlaceholder\RegularPlaceholder;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;

final class PlaceholderFactory extends AbstractComponentPresentationObjectFactory
{
    public function forJobList(): IndexPlaceholder
    {
        return new IndexPlaceholder();
    }

    public function forJobApplication(): ApplyPlaceholder
    {
        return new ApplyPlaceholder();
    }

    public function forJob(): RegularPlaceholder
    {
        return new RegularPlaceholder();
    }

    public function forJobNotification(): RegularPlaceholder
    {
        return new RegularPlaceholder();
    }
}
