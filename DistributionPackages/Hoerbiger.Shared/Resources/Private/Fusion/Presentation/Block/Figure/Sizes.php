<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Figure;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
final class Sizes
{
    /**
     * @var array<string,string>
     */
    private array $qualifiedWidths;

    private string $finalWidth;

    /**
     * @param array<string,string> $array
     */
    public function __construct(array $array, string $finalWidth)
    {
        $this->qualifiedWidths = $array;
        $this->finalWidth = $finalWidth;
    }

    public function toString(): string
    {
        $sizes = '';
        foreach ($this->qualifiedWidths as $qualifier => $width) {
            $sizes .= $qualifier . ' ' .  $width . ',';
        }
        $sizes .= $this->finalWidth;

        return $sizes;
    }

    public function getSizes(): string
    {
        return $this->toString();
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
