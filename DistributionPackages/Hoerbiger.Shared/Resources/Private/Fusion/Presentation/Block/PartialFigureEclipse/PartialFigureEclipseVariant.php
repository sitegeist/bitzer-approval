<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\PartialFigureEclipse;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

/**
 * @Flow\Proxy(false)
 */
final class PartialFigureEclipseVariant implements PseudoEnumInterface
{
    const VARIANT_IMAGELEFT = 'imageLeft';
    const VARIANT_IMAGERIGHT = 'imageRight';

    /**
     * @var array<string,self>|self[]
     */
    private static array $instances;

    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function from(?string $string = null): self
    {
        if($string === null) {
            $string = self::VARIANT_IMAGELEFT;
        }

        if (!isset(self::$instances[$string])) {
            if ($string !== self::VARIANT_IMAGELEFT
                && $string !== self::VARIANT_IMAGERIGHT) {
                throw PartialFigureEclipseVariantIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
            }
            self::$instances[$string] = new self($string);
        }

        return self::$instances[$string];
    }

    public static function imageLeft(): self
    {
        return self::from(self::VARIANT_IMAGELEFT);
    }

    public static function imageRight(): self
    {
        return self::from(self::VARIANT_IMAGERIGHT);
    }

    public function getIsImageLeft(): bool
    {
        return $this->value === self::VARIANT_IMAGELEFT;
    }

    public function getIsImageRight(): bool
    {
        return $this->value === self::VARIANT_IMAGERIGHT;
    }

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            self::from(self::VARIANT_IMAGELEFT),
            self::from(self::VARIANT_IMAGERIGHT)
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
