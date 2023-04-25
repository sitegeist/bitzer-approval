<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Button;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

/**
 * @Flow\Proxy(false)
 */
final class ButtonType implements PseudoEnumInterface
{
    const TYPE_REGULAR = 'regular';
    const TYPE_LINK = 'link';

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
            if ($string !== self::TYPE_LINK && $string !== self::TYPE_REGULAR) {
                throw ButtonTypeIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
            }
            self::$instances[$string] = new self($string);
        }

        return self::$instances[$string];
    }

    public static function link(): self
    {
        return self::from(self::TYPE_LINK);
    }

    public static function regular(): self
    {
        return self::from(self::TYPE_REGULAR);
    }

    public function getIsLink(): bool
    {
        return $this->value === self::TYPE_LINK;
    }

    public function getIsRegular(): bool
    {
        return $this->value === self::TYPE_REGULAR;
    }

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            self::from(self::TYPE_LINK)
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
