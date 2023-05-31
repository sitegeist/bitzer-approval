<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Figure;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
enum PresetIdentifier: string
{
    case IDENTIFIER_XS = 'xs';
    case IDENTIFIER_SM = 'sm';
    case IDENTIFIER_MD = 'md';
    case IDENTIFIER_LG = 'lg';
    case IDENTIFIER_XL = 'xl';
    case IDENTIFIER_PDF = 'pdf';


    public static function fromString(string $string): self|null
    {
        return match ($string){
            'xs' => self::IDENTIFIER_XS,
            'sm' => self::IDENTIFIER_SM,
            'md' => self::IDENTIFIER_MD,
            'lg' => self::IDENTIFIER_LG,
            'xl' => self::IDENTIFIER_XL,
            'pdf' => self::IDENTIFIER_PDF,
            default => null
        };
    }

}
