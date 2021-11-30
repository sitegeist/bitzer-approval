<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\AffiliateDirectory\Presentation\Application\AffiliateFinder;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.AffiliateDirectory package.
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
final class Affiliate implements \JsonSerializable
{
    private string $legalName;
    private string $streetAddress;
    private string $postalCode;
    private string $addressLocality;
    private string $addressCountry;
    private string $email;
    private ?string $telephone;
    private float $latitude;
    private float $longitude;
    /**
     * @var array<int,string>
     */
    private array $tags;

    /**
     * @param array<int,string> $tags
     */
    public function __construct(
        string $legalName,
        string $streetAddress,
        string $postalCode,
        string $addressLocality,
        string $addressCountry,
        string $email,
        ?string $telephone,
        float $latitude,
        float $longitude,
        array $tags
    ) {
        $this->legalName = $legalName;
        $this->streetAddress = $streetAddress;
        $this->postalCode = $postalCode;
        $this->addressLocality = $addressLocality;
        $this->addressCountry = $addressCountry;
        $this->email = $email;
        $this->telephone = $telephone;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->tags = $tags;
    }

    public static function dummy(): self
    {
        return new self(
            'Peter klaus die fledermaus',
            'Poßmoorweg 2',
            '22312',
            'Hamburg',
            'Deutschland',
            'fledermaus@sitegeist.de',
            '004512345678',
            53.5853532,
            10.0039283,
            []
        );
    }

    /**
     * @return array<string,mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'legalName' => $this->legalName,
            'streetAddress' => $this->streetAddress,
            'postalCode' => $this->postalCode,
            'addressLocality' => $this->addressLocality,
            'addressCountry' => $this->addressCountry,
            'email' => $this->email,
            'telephone' => $this->telephone,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'tags' => $this->tags
        ];
    }
}
