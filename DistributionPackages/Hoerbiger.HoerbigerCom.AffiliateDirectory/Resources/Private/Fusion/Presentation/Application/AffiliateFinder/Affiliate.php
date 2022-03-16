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
    private string $identifier;
    private string $legalName;
    private string $streetAddress;
    private string $postalCode;
    private string $addressLocality;
    private string $addressCountry;
    private string $email;
    private ?string $telephone;
    private float $latitude;
    private float $longitude;

    public function __construct(
        string $identifier,
        string $legalName,
        string $streetAddress,
        string $postalCode,
        string $addressLocality,
        string $addressCountry,
        string $email,
        ?string $telephone,
        float $latitude,
        float $longitude
    ) {
        $this->identifier = $identifier;
        $this->legalName = $legalName;
        $this->streetAddress = $streetAddress;
        $this->postalCode = $postalCode;
        $this->addressLocality = $addressLocality;
        $this->addressCountry = $addressCountry;
        $this->email = $email;
        $this->telephone = $telephone;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public static function dummy(): self
    {
        return new self(
            '123',
            'Peter klaus die fledermaus',
            'Poßmoorweg 2',
            '22312',
            'Hamburg',
            'Deutschland',
            'fledermaus@sitegeist.de',
            '004512345678',
            53.5853532,
            10.0039283
        );
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getLegalName(): string
    {
        return $this->legalName;
    }

    public function getStreetAddress(): string
    {
        return $this->streetAddress;
    }

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function getAddressLocality(): string
    {
        return $this->addressLocality;
    }

    public function getAddressCountry(): string
    {
        return $this->addressCountry;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    /**
     * @return array<string,mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'identifier' => $this->identifier,
            'legalName' => $this->legalName,
            'streetAddress' => $this->streetAddress,
            'postalCode' => $this->postalCode,
            'addressLocality' => $this->addressLocality,
            'addressCountry' => $this->addressCountry,
            'email' => $this->email,
            'telephone' => $this->telephone,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude
        ];
    }
}
