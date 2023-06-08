<?php declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Catalog\Domain;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.Catalog package.
 */

use Hoerbiger\Shared\Presentation\EnumProvider\Theme\Theme;
use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
final class ProductClass
{
    const CLASS_EXCITE = 'excite';
    const CLASS_EXCITE_SERVICE = 'exciteService';
    const CLASS_EXPERIENCE = 'experience';
    const CLASS_EXPERIENCE_SERVICE = 'experienceService';
    const CLASS_LITE = 'lite';
    const CLASS_LITE_SERVICE = 'liteService';
    const CLASS_SERVICE = 'service';
    const CLASS_DEFAULT = 'default';

    /**
     * @var array<string,self>|self[]
     */
    private static array $instances;

    private string $class;

    private function __construct(string $class)
    {
        $this->class = $class;
    }

    public static function from(string $string): self
    {
        if (!isset(self::$instances[$string])) {
            if ($string !== self::CLASS_EXCITE
                && $string !== self::CLASS_EXCITE_SERVICE
                && $string !== self::CLASS_EXPERIENCE
                && $string !== self::CLASS_EXPERIENCE_SERVICE
                && $string !== self::CLASS_LITE
                && $string !== self::CLASS_LITE_SERVICE
                && $string !== self::CLASS_SERVICE
                && $string !== self::CLASS_DEFAULT) {
                throw ProductClassIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
            }
            self::$instances[$string] = new self($string);
        }

        return self::$instances[$string];
    }

    public static function tryFrom(?string $string): ?self
    {
        return !is_null($string)
            ? self::from($string)
            : null;
    }

    public static function excite(): self
    {
        return self::from(self::CLASS_EXCITE);
    }

    public static function exciteService(): self
    {
        return self::from(self::CLASS_EXCITE_SERVICE);
    }

    public static function experience(): self
    {
        return self::from(self::CLASS_EXPERIENCE);
    }

    public static function experienceService(): self
    {
        return self::from(self::CLASS_EXPERIENCE_SERVICE);
    }

    public static function lite(): self
    {
        return self::from(self::CLASS_LITE);
    }

    public static function liteService(): self
    {
        return self::from(self::CLASS_LITE_SERVICE);
    }

    public static function service(): self
    {
        return self::from(self::CLASS_SERVICE);
    }

    public static function unbranded(): self
    {
        return self::from(self::CLASS_DEFAULT);
    }

    public function getIsExcite(): bool
    {
        return $this->class === self::CLASS_EXCITE;
    }

    public function getIsExciteService(): bool
    {
        return $this->class === self::CLASS_EXCITE_SERVICE;
    }

    public function getIsExperience(): bool
    {
        return $this->class === self::CLASS_EXPERIENCE;
    }

    public function getIsExperienceService(): bool
    {
        return $this->class === self::CLASS_EXPERIENCE_SERVICE;
    }

    public function getIsLite(): bool
    {
        return $this->class === self::CLASS_LITE;
    }

    public function getIsLiteService(): bool
    {
        return $this->class === self::CLASS_LITE_SERVICE;
    }

    public function getIsService(): bool
    {
        return $this->class === self::CLASS_SERVICE;
    }

    public function getIsDefault(): bool
    {
        return $this->class === self::CLASS_DEFAULT;
    }

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            self::from(self::CLASS_EXCITE),
            self::from(self::CLASS_EXCITE_SERVICE),
            self::from(self::CLASS_EXPERIENCE),
            self::from(self::CLASS_EXPERIENCE_SERVICE),
            self::from(self::CLASS_LITE),
            self::from(self::CLASS_LITE_SERVICE),
            self::from(self::CLASS_SERVICE),
            self::from(self::CLASS_DEFAULT)
        ];
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getLabel(): string
    {
        switch ($this->class) {
            case self::CLASS_EXCITE:
                return 'Excite';
            case self::CLASS_EXCITE_SERVICE:
                return 'Excite Service';
            case self::CLASS_EXPERIENCE:
                return 'Experience';
            case self::CLASS_EXPERIENCE_SERVICE:
                return 'Experience Service';
            case self::CLASS_LITE:
                return 'Lite';
            case self::CLASS_LITE_SERVICE:
                return 'Lite Service';
            case self::CLASS_SERVICE:
                return 'Service';
            case self::CLASS_DEFAULT:
            default:
                return 'Standard';
        }
    }

    public function getImageFileName(): string
    {
        switch ($this->class) {
            case self::CLASS_EXCITE:
                return 'EXCITE_CMYK.svg';
            case self::CLASS_EXCITE_SERVICE:
                return 'EXCITE_SERVICE_CMYK_NAM.svg';
            case self::CLASS_EXPERIENCE:
                return 'EXPERIENCE_CMYK.svg';
            case self::CLASS_EXPERIENCE_SERVICE:
                return 'EXPERIENCE_SERVICE_CMYK_NAM.svg';
            case self::CLASS_LITE:
                return 'LITE_CMYK.svg';
            case self::CLASS_LITE_SERVICE:
                return 'LITE_SERVICE_CMYK_NAM.svg';
            case self::CLASS_SERVICE:
                return 'SERVICE.svg';
            case self::CLASS_DEFAULT:
            default:
                return 'Standard';
        }
    }

    public function toTheme(): Theme
    {
        return match ($this->class) {
            self::CLASS_EXCITE, self::CLASS_EXCITE_SERVICE => Theme::VALUE_EXCITE,
            self::CLASS_EXPERIENCE, self::CLASS_EXPERIENCE_SERVICE => Theme::VALUE_EXPERIENCE,
            self::CLASS_LITE, self::CLASS_LITE_SERVICE => Theme::VALUE_LITE,
            self::CLASS_SERVICE => Theme::VALUE_SERVICE,
            default => Theme::VALUE_DEFAULT,
        };
    }

    public function __toString(): string
    {
        return $this->class;
    }
}
