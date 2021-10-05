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
final class FigureObjectFit implements PseudoEnumInterface
{
    const FIT_COVER = 'cover';
    const FIT_CONTAIN = 'contain';

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
            if ($string !== self::FIT_COVER
                && $string !== self::FIT_CONTAIN) {
                throw FigureObjectFitIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
            }
            self::$instances[$string] = new self($string);
        }

        return self::$instances[$string];
    }

    public static function cover(): self
    {
        return self::from(self::FIT_COVER);
    }

    public static function contain(): self
    {
        return self::from(self::FIT_CONTAIN);
    }

    public function getIsCover(): bool
    {
        return $this->value === self::FIT_COVER;
    }

    public function getIsContain(): bool
    {
        return $this->value === self::FIT_CONTAIN;
    }

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            self::from(self::FIT_COVER),
            self::from(self::FIT_CONTAIN)
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
