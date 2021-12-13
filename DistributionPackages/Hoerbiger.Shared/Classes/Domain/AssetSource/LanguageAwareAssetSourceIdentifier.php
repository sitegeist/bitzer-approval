<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Domain\AssetSource;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
final class LanguageAwareAssetSourceIdentifier
{
    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function fromString(string $string): self
    {
        if ($string !== 'neos' && \mb_strpos($string, 'neos-') !== 0) {
            throw LanguageAwareAssetSourceIdentifierIsInvalid::becauseItMustBeOrStartWithNeos($string);
        }

        return new self($string);
    }

    public static function fromLanguage(string $language): self
    {
        if ($language === 'en') {
            return new self('neos');
        }

        return new self('neos-' . $language);
    }

    /**
     * @param array<string,mixed> $dimensions
     */
    public static function fromDimensions(array $dimensions): self
    {
        $languages = $dimensions['language'];

        return self::fromLanguage(reset($languages));
    }

    public function getLanguage(): string
    {
        if ($this->value === 'neos') {
            return 'en';
        }

        return \str_replace('neos-', '', $this->value);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
