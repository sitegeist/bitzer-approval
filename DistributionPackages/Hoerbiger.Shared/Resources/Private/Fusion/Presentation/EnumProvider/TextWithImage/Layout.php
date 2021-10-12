<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\EnumProvider\TextWithImage;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

/**
 * @Flow\Proxy(false)
 */
final class Layout implements PseudoEnumInterface
{
    const VALUE_IMAGEFIRST = 'imageFirst';
    const VALUE_IMAGELAST = 'imageLast';

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
            $string = self::VALUE_IMAGEFIRST;
        }

        if (!isset(self::$instances[$string])) {
            if ($string !== self::VALUE_IMAGEFIRST
                && $string !== self::VALUE_IMAGELAST) {
                throw LayoutIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
            }
            self::$instances[$string] = new self($string);
        }

        return self::$instances[$string];
    }

    public static function imageFirst(): self
    {
        return self::from(self::VALUE_IMAGEFIRST);
    }

    public static function imageLast(): self
    {
        return self::from(self::VALUE_IMAGELAST);
    }

    public function getIsImageFirst(): bool
    {
        return $this->value === self::VALUE_IMAGEFIRST;
    }

    public function getIsImageLast(): bool
    {
        return $this->value === self::VALUE_IMAGELAST;
    }

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            self::from(self::VALUE_IMAGEFIRST),
            self::from(self::VALUE_IMAGELAST)
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
