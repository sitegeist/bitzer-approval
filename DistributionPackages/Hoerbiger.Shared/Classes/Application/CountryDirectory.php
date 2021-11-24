<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Application;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\I18n\Cldr\CldrRepository;
use Neos\Flow\I18n\Locale;

class CountryDirectory
{
    /**
     * @Flow\Inject
     * @var CldrRepository
     */
    protected $cldrRepository;

    public function findAll(Locale $locale): Countries
    {
        $countries = [];
        foreach ($this->cldrRepository->getModelForLocale($locale, 'main')
                    ->getRawData('localeDisplayNames/territories') as $rawCountryCode => $countryName) {
            $matches = [];
            \preg_match('/@type="(?<countryCode>[A-Z]+)"/s', $rawCountryCode, $matches);
            if (isset($matches['countryCode']) && is_string($matches['countryCode'])) {
                $countries[$matches['countryCode']] = new Country($matches['countryCode'], $countryName);
            }
        }

        return new Countries($countries);
    }

    public function findCountryName(string $countryCode, Locale $locale): string
    {
        return $this->cldrRepository->getModelForLocale($locale, 'main')
            ->getRawData('localeDisplayNames/territories/territory[@type="' . $countryCode . '"]') ?: $countryCode;
    }
}
