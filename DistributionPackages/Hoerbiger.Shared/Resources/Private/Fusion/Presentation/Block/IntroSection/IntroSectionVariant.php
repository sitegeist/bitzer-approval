<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\IntroSection;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\ContentRepository\Domain\NodeType\NodeTypeName;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

/**
 * @Flow\Proxy(false)
 */
final class IntroSectionVariant implements PseudoEnumInterface
{
    const VARIANT_FULLMEDIA = 'fullMedia';
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
            if ($string !== self::VARIANT_FULLMEDIA
                && $string !== self::VARIANT_DUO) {
                throw IntroSectionVariantIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
            }
            self::$instances[$string] = new self($string);
        }

        return self::$instances[$string];
    }

    public static function fromNodeTypeName(NodeTypeName $nodeTypeName): self
    {
        switch ($nodeTypeName->getValue()) {
            case 'Hoerbiger.HoerbigerCom.Catalog:Document.Product':
            case 'Hoerbiger.HoerbigerCom.Catalog:Document.ProductCategory':
                return self::duo();
            case 'Hoerbiger.HoerbigerCom:Document.WebPage':
            case 'Hoerbiger.HoerbigerCom:Document.Error.404':
            case 'Hoerbiger.HoerbigerCom.Catalog:Document.Catalog':
            default:
                return self::fullMedia();
        }
    }

    public static function fullMedia(): self
    {
        return self::from(self::VARIANT_FULLMEDIA);
    }

    public static function duo(): self
    {
        return self::from(self::VARIANT_DUO);
    }

    public function getIsFullMedia(): bool
    {
        return $this->value === self::VARIANT_FULLMEDIA;
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
            self::from(self::VARIANT_FULLMEDIA),
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
