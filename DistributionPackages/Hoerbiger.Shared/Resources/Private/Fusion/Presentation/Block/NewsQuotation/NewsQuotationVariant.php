<?php

declare(strict_types=1);

namespace Hoerbiger\Shared\Presentation\Block\NewsQuotation;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\EnumProvider\Theme\Theme;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

/**
 * @Flow\Proxy(false)
 */
enum NewsQuotationVariant: string
{
    case VARIANT_REGULAR = 'regular';

    public static function fromString(string $string): self|null
    {
        return match ($string){
            'regular' => self::VARIANT_REGULAR,
            default => null
        };
    }

    public static function tryFromTheme(?Theme $theme): self
    {
        if (!$theme || $theme->getValue() == 'default') {
            return self::VARIANT_REGULAR;
        }
        return self::fromString($theme->getValue());
    }

}
