<?php declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Domain\Stuff;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
final class StuffQuery
{
    private string $language;

    private ?SearchTerm $searchTerm;

    private SortingProperty $sortingProperty;

    private ?SearchPeriod $period;

    private SearchTypes $types;

    private SearchPage $page;

    public function __construct(
        string $language,
        ?SearchTerm $searchTerm,
        SortingProperty $sortingProperty,
        ?SearchPeriod $period,
        SearchTypes $types,
        SearchPage $page
    ) {
        $this->language = $language;
        $this->searchTerm = $searchTerm;
        $this->sortingProperty = $sortingProperty;
        $this->period = $period;
        $this->types = $types;
        $this->page = $page;
    }

    public static function fromJsonString(string $jsonString): self
    {
        return self::fromArray(\json_decode($jsonString, true));
    }

    /**
     * @param array<string,mixed> $array
     */
    public static function fromArray(array $array): self
    {
        return new self(
            $array['language'],
            $array['searchTerm'] ? SearchTerm::fromString($array['searchTerm']) : null,
            SortingProperty::from($array['sortingProperty']),
            $array['period'] ? SearchPeriod::from($array['period']) : null,
            new SearchTypes(...array_map(function (string $string): SearchType {
                return SearchType::from($string);
            }, $array['types'])),
            SearchPage::fromInt($array['page'])
        );
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function getSearchTerm(): ?SearchTerm
    {
        return $this->searchTerm;
    }

    public function getSortingProperty(): SortingProperty
    {
        return $this->sortingProperty;
    }

    public function getPeriod(): ?SearchPeriod
    {
        return $this->period;
    }

    public function getTypes(): SearchTypes
    {
        return $this->types;
    }

    public function getPage(): SearchPage
    {
        return $this->page;
    }
}
