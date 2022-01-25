<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Link;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\EnumProvider\Theme\Theme;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

/**
 * @Flow\Proxy(false)
 */
final class LinkVariant implements PseudoEnumInterface
{
    const VARIANT_CONTAINER = 'container';
    const VARIANT_LABEL = 'label';
    const VARIANT_TOUCHICON = 'touchIcon';
    const VARIANT_NOSTYLES = 'noStyles';
    const VARIANT_SITEFOOTER = 'siteFooter';
    const VARIANT_PLAINLABEL = 'plainLabel';
    const VARIANT_ICONLABEL = 'iconLabel';
    const VARIANT_LABELEXCITE = 'labelExcite';
    const VARIANT_LABELEXPERIENCE = 'labelExperience';
    const VARIANT_LABELLITE = 'labelLite';
    const VARIANT_LABELSERVICE = 'labelService';

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
            if ($string !== self::VARIANT_CONTAINER
                && $string !== self::VARIANT_LABEL
                && $string !== self::VARIANT_TOUCHICON
                && $string !== self::VARIANT_NOSTYLES
                && $string !== self::VARIANT_SITEFOOTER
                && $string !== self::VARIANT_PLAINLABEL
                && $string !== self::VARIANT_ICONLABEL
                && $string !== self::VARIANT_LABELEXCITE
                && $string !== self::VARIANT_LABELEXPERIENCE
                && $string !== self::VARIANT_LABELLITE
                && $string !== self::VARIANT_LABELSERVICE) {
                throw LinkVariantIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
            }
            self::$instances[$string] = new self($string);
        }

        return self::$instances[$string];
    }

    public static function tryFromTheme(?Theme $theme): ?self
    {
        return $theme
            ? self::from('label' . (ucfirst($theme->getValue())))
            : null;
    }

    public static function container(): self
    {
        return self::from(self::VARIANT_CONTAINER);
    }

    public static function label(): self
    {
        return self::from(self::VARIANT_LABEL);
    }

    public static function touchIcon(): self
    {
        return self::from(self::VARIANT_TOUCHICON);
    }

    public static function noStyles(): self
    {
        return self::from(self::VARIANT_NOSTYLES);
    }

    public static function siteFooter(): self
    {
        return self::from(self::VARIANT_SITEFOOTER);
    }

    public static function plainLabel(): self
    {
        return self::from(self::VARIANT_PLAINLABEL);
    }

    public static function iconLabel(): self
    {
        return self::from(self::VARIANT_ICONLABEL);
    }

    public static function labelExcite(): self
    {
        return self::from(self::VARIANT_LABELEXCITE);
    }

    public static function labelExperience(): self
    {
        return self::from(self::VARIANT_LABELEXPERIENCE);
    }

    public static function labelLite(): self
    {
        return self::from(self::VARIANT_LABELLITE);
    }

    public static function labelService(): self
    {
        return self::from(self::VARIANT_LABELSERVICE);
    }

    public function getIsContainer(): bool
    {
        return $this->value === self::VARIANT_CONTAINER;
    }

    public function getIsLabel(): bool
    {
        return $this->value === self::VARIANT_LABEL;
    }

    public function getIsTouchIcon(): bool
    {
        return $this->value === self::VARIANT_TOUCHICON;
    }

    public function getIsNoStyles(): bool
    {
        return $this->value === self::VARIANT_NOSTYLES;
    }

    public function getIsSiteFooter(): bool
    {
        return $this->value === self::VARIANT_SITEFOOTER;
    }

    public function getIsPlainLabel(): bool
    {
        return $this->value === self::VARIANT_PLAINLABEL;
    }

    public function getIsIconLabel(): bool
    {
        return $this->value === self::VARIANT_ICONLABEL;
    }

    public function getIsLabelExcite(): bool
    {
        return $this->value === self::VARIANT_LABELEXCITE;
    }

    public function getIsLabelExperience(): bool
    {
        return $this->value === self::VARIANT_LABELEXPERIENCE;
    }

    public function getIsLabelLite(): bool
    {
        return $this->value === self::VARIANT_LABELLITE;
    }

    public function getIsLabelService(): bool
    {
        return $this->value === self::VARIANT_LABELSERVICE;
    }

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            self::from(self::VARIANT_CONTAINER),
            self::from(self::VARIANT_LABEL),
            self::from(self::VARIANT_TOUCHICON),
            self::from(self::VARIANT_NOSTYLES),
            self::from(self::VARIANT_SITEFOOTER),
            self::from(self::VARIANT_PLAINLABEL),
            self::from(self::VARIANT_ICONLABEL),
            self::from(self::VARIANT_LABELEXCITE),
            self::from(self::VARIANT_LABELEXPERIENCE),
            self::from(self::VARIANT_LABELLITE),
            self::from(self::VARIANT_LABELSERVICE)
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
