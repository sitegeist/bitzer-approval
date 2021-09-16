<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Figure;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

/**
 * @Flow\Proxy(false)
 */
final class FigureObjectPosition implements PseudoEnumInterface
{
    const POSITION_TOPLEFT = 'topLeft';
    const POSITION_TOP = 'top';
    const POSITION_TOPRIGHT = 'topRight';
    const POSITION_LEFT = 'left';
    const POSITION_CENTER = 'center';
    const POSITION_RIGHT = 'right';
    const POSITION_BOTTOMLEFT = 'bottomLeft';
    const POSITION_BOTTOM = 'bottom';
    const POSITION_BOTTOMRIGHT = 'bottomRight';

    /**
     * @var array<string,self>|self[]
     */
    private static array $instances;

    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function from(string $string): self
    {
        if (!isset(self::$instances[$string])) {
            if ($string !== self::POSITION_TOPLEFT
                && $string !== self::POSITION_TOP
                && $string !== self::POSITION_TOPRIGHT
                && $string !== self::POSITION_LEFT
                && $string !== self::POSITION_CENTER
                && $string !== self::POSITION_RIGHT
                && $string !== self::POSITION_BOTTOMLEFT
                && $string !== self::POSITION_BOTTOM
                && $string !== self::POSITION_BOTTOMRIGHT) {
                throw FigureObjectPositionIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
            }
            self::$instances[$string] = new self($string);
        }

        return self::$instances[$string];
    }

    public static function topLeft(): self
    {
        return self::from(self::POSITION_TOPLEFT);
    }

    public static function top(): self
    {
        return self::from(self::POSITION_TOP);
    }

    public static function topRight(): self
    {
        return self::from(self::POSITION_TOPRIGHT);
    }

    public static function left(): self
    {
        return self::from(self::POSITION_LEFT);
    }

    public static function center(): self
    {
        return self::from(self::POSITION_CENTER);
    }

    public static function right(): self
    {
        return self::from(self::POSITION_RIGHT);
    }

    public static function bottomLeft(): self
    {
        return self::from(self::POSITION_BOTTOMLEFT);
    }

    public static function bottom(): self
    {
        return self::from(self::POSITION_BOTTOM);
    }

    public static function bottomRight(): self
    {
        return self::from(self::POSITION_BOTTOMRIGHT);
    }

    public function getIsTopLeft(): bool
    {
        return $this->value === self::POSITION_TOPLEFT;
    }

    public function getIsTop(): bool
    {
        return $this->value === self::POSITION_TOP;
    }

    public function getIsTopRight(): bool
    {
        return $this->value === self::POSITION_TOPRIGHT;
    }

    public function getIsLeft(): bool
    {
        return $this->value === self::POSITION_LEFT;
    }

    public function getIsCenter(): bool
    {
        return $this->value === self::POSITION_CENTER;
    }

    public function getIsRight(): bool
    {
        return $this->value === self::POSITION_RIGHT;
    }

    public function getIsBottomLeft(): bool
    {
        return $this->value === self::POSITION_BOTTOMLEFT;
    }

    public function getIsBottom(): bool
    {
        return $this->value === self::POSITION_BOTTOM;
    }

    public function getIsBottomRight(): bool
    {
        return $this->value === self::POSITION_BOTTOMRIGHT;
    }

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            self::from(self::POSITION_TOPLEFT),
            self::from(self::POSITION_TOP),
            self::from(self::POSITION_TOPRIGHT),
            self::from(self::POSITION_LEFT),
            self::from(self::POSITION_CENTER),
            self::from(self::POSITION_RIGHT),
            self::from(self::POSITION_BOTTOMLEFT),
            self::from(self::POSITION_BOTTOM),
            self::from(self::POSITION_BOTTOMRIGHT)
        ];
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
