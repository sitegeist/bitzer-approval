<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Box;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

/**
 * @Flow\Proxy(false)
 */
final class BoxVariant implements PseudoEnumInterface
{
    const VARIANT_TEXTCENTER = 'textCenter';

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
            if ($string !== self::VARIANT_TEXTCENTER) {
                throw BoxVariantIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
            }
            self::$instances[$string] = new self($string);
        }

        return self::$instances[$string];
    }

    public static function textCenter(): self
    {
        return self::from(self::VARIANT_TEXTCENTER);
    }

    public function getIsTextCenter(): bool
    {
        return $this->value === self::VARIANT_TEXTCENTER;
    }

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            self::from(self::VARIANT_TEXTCENTER)
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
