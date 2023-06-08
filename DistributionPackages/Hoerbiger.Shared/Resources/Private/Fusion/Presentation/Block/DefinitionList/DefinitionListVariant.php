<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\DefinitionList;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
enum DefinitionListVariant: string
{
    case VARIANT_REGULAR = 'regular';

    public static function fromString(string $string): self|null
    {
        return match ($string){
            'regular' => self::VARIANT_REGULAR,
            default => null
        };
    }

}
