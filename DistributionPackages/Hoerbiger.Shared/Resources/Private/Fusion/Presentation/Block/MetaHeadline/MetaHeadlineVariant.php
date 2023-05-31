<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\MetaHeadline;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

/**
 * @Flow\Proxy(false)
 */
enum MetaHeadlineVariant: string
{
    case VARIANT_REGULAR = 'regular';

    public static function fromString(string $string): self
    {
        return match ($string){
            'regular' => self::VARIANT_REGULAR,
            default => null
        };
    }

}
