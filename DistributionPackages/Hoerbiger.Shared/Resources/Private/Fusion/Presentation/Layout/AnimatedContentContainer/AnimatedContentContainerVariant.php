<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\AnimatedContentContainer;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

/**
 * @Flow\Proxy(false)
 */
final class AnimatedContentContainerVariant implements PseudoEnumInterface
{
    const VARIANT_REGULAR = 'regular';
    const VARIANT_FADE_IN = 'fadeIn';
    const VARIANT_FADE_AND_TRANSLATE = 'fadeAndTranslate';

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
            if ($string !== self::VARIANT_REGULAR
                && $string !== self::VARIANT_FADE_IN
                && $string !== self::VARIANT_FADE_AND_TRANSLATE) {
                throw AnimatedContentContainerVariantIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
            }
            self::$instances[$string] = new self($string);
        }

        return self::$instances[$string];
    }

    public static function regular(): self
    {
        return self::from(self::VARIANT_REGULAR);
    }

    public static function fadeIn(): self
    {
        return self::from(self::VARIANT_FADE_IN);
    }

    public static function fadeAndTranslate(): self
    {
        return self::from(self::VARIANT_FADE_AND_TRANSLATE);
    }

    public function getIsRegular(): bool
    {
        return $this->value === self::VARIANT_REGULAR;
    }

    public function getIsFadeIn(): bool
    {
        return $this->value === self::VARIANT_FADE_IN;
    }

    public function getIsFadeAndTranslate(): bool
    {
        return $this->value === self::VARIANT_FADE_AND_TRANSLATE;
    }

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            self::from(self::VARIANT_REGULAR),
            self::from(self::VARIANT_FADE_IN),
            self::from(self::VARIANT_FADE_AND_TRANSLATE)
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
