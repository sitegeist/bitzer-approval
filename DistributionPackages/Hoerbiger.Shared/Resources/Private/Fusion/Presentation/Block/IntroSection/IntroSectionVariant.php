<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\IntroSection;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\HoerbigerCom\Presentation\EnumProvider\Introduction\IntroSectionVariant as IntroductionIntroSectionVariant;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

/**
 * @Flow\Proxy(false)
 */
final class IntroSectionVariant implements PseudoEnumInterface
{
    const VARIANT_FULLMEDIATEXTLEFT = 'fullMediaTextLeft';
    const VARIANT_FULLMEDIATEXTRIGHT = 'fullMediaTextRight';
    const VARIANT_DUO = 'duo';

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
            if ($string !== self::VARIANT_FULLMEDIATEXTLEFT
                && $string !== self::VARIANT_FULLMEDIATEXTRIGHT
                && $string !== self::VARIANT_DUO) {
                throw IntroSectionVariantIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
            }
            self::$instances[$string] = new self($string);
        }

        return self::$instances[$string];
    }

    public static function fullMediaTextLeft(): self
    {
        return self::from(self::VARIANT_FULLMEDIATEXTLEFT);
    }

    public static function fullMediaTextRight(): self
    {
        return self::from(self::VARIANT_FULLMEDIATEXTRIGHT);
    }

    public static function duo(): self
    {
        return self::from(self::VARIANT_DUO);
    }

    public function getIsFullMediaTextLeft(): bool
    {
        return $this->value === self::VARIANT_FULLMEDIATEXTLEFT;
    }

    public function getIsFullMediaTextRight(): bool
    {
        return $this->value === self::VARIANT_FULLMEDIATEXTRIGHT;
    }

    public function getIsDuo(): bool
    {
        return $this->value === self::VARIANT_DUO;
    }

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            self::from(self::VARIANT_FULLMEDIATEXTLEFT),
            self::from(self::VARIANT_FULLMEDIATEXTRIGHT),
            self::from(self::VARIANT_DUO)
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
