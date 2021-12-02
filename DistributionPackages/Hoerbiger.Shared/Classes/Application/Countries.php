<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Application;

/*
 * This file is part of the Hoerbiger.Shared package.
 */


use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 * @implements \IteratorAggregate<string,Country>
 */
final class Countries implements \IteratorAggregate
{
    /**
     * @var array<string,Country>
     */
    private array $countries;

    /**
     * @param array<string,Country> $countries
     */
    public function __construct(array $countries)
    {
        foreach ($countries as $countryCode => $country) {
            if (!is_string($countryCode) || empty($countryCode)) {
                throw new \InvalidArgumentException('Countries must be indexed by country code', 1637088647);
            }
            if (!$country instanceof Country) {
                throw new \InvalidArgumentException(
                    'Countries collections may only contain Country objects',
                    1637088639
                );
            }
        }

        $this->countries = $countries;
    }

    public function sortForLanguage(string $language): self
    {
        $countries = $this->countries;
        uasort($countries, function (Country $countryA, Country $countryB) use ($language) {
            $countryCodeA = $countryA->getCode();
            $countryCodeB = $countryB->getCode();

            switch ($language) {
                case 'de':
                    if ($countryCodeA === 'DE'
                        || $countryCodeA === 'AT' && $countryCodeB !== 'DE'
                        || $countryCodeA === 'CH' && $countryCodeB !== 'DE' && $countryCodeB !== 'AT'
                        || $countryCodeA === 'LU' && $countryCodeB !== 'DE' && $countryCodeB !== 'AT'
                            && $countryCodeB !== 'CH') {
                        return -1;
                    }
                    if ($countryCodeB === 'DE'
                        || $countryCodeB === 'AT' && $countryCodeA !== 'DE'
                        || $countryCodeB === 'CH' && $countryCodeA !== 'DE' && $countryCodeA !== 'AT'
                        || $countryCodeB === 'LU' && $countryCodeA !== 'DE' && $countryCodeA !== 'AT'
                            && $countryCodeA !== 'CH') {
                        return 1;
                    }
                    return $countryA->getName() <=> $countryB->getName();
                default:
                    return $countryA->getName() <=> $countryB->getName();
            }
        });

        return new self($countries);
    }

    /**
     * @return \ArrayIterator<string,Country>|Country[]
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->countries);
    }

    /**
     * @return array<string,Country>|Country[]
     */
    public function getCountries(): array
    {
        return $this->countries;
    }
}
