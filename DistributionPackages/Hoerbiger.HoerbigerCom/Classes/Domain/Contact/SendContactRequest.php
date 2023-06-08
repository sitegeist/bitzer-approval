<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Domain\Contact;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use Neos\ContentRepository\Domain\NodeAggregate\NodeAggregateIdentifier;
use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
final class SendContactRequest
{
    private string $language;

    private ?CustomerGroupIdentifier $customerGroup;

    private ?NodeAggregateIdentifier $applicationWorld;

    private ?Salutation $salutation;

    private string $givenName;

    private string $familyName;

    private string $emailAddress;

    private ?string $worksFor;

    private ?string $telephone;

    private ?string $streetAddress;

    private ?string $postalCode;

    private ?string $addressLocality;

    private ?string $addressCountry;

    private string $message;

    private function __construct(
        string $language,
        ?CustomerGroupIdentifier $customerGroup,
        ?NodeAggregateIdentifier $applicationWorld,
        ?Salutation $salutation,
        string $givenName,
        string $familyName,
        string $emailAddress,
        ?string $worksFor,
        ?string $telephone,
        ?string $streetAddress,
        ?string $postalCode,
        ?string $addressLocality,
        ?string $addressCountry,
        string $message
    ) {
        $this->language = $language;
        $this->customerGroup = $customerGroup;
        $this->applicationWorld = $applicationWorld;
        $this->salutation = $salutation;
        $this->givenName = $givenName;
        $this->familyName = $familyName;
        $this->emailAddress = $emailAddress;
        $this->worksFor = $worksFor;
        $this->telephone = $telephone;
        $this->streetAddress = $streetAddress;
        $this->postalCode = $postalCode;
        $this->addressLocality = $addressLocality;
        $this->addressCountry = $addressCountry;
        $this->message = $message;
    }

    public static function fromJsonString(string $jsonString): self
    {
        $array = json_decode($jsonString, true);

        return new self(
            $array['language'],
            isset($array['customerGroup']) ? CustomerGroupIdentifier::from($array['customerGroup']) : null,
            isset($array['applicationWorld']) ? NodeAggregateIdentifier::fromString($array['applicationWorld']) : null,
            isset($array['salutation']) ? Salutation::from($array['salutation']) : null,
            $array['givenName'],
            $array['familyName'],
            $array['emailAddress'],
            $array['worksFor'] ?? null,
            $array['telephone'] ?? null,
            $array['streetAddress'] ?? null,
            $array['postalCode'] ?? null,
            $array['addressLocality'] ?? null,
            $array['addressCountry'] ?? null,
            $array['message']
        );
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function getCustomerGroup(): ?CustomerGroupIdentifier
    {
        return $this->customerGroup;
    }

    public function getApplicationWorld(): ?NodeAggregateIdentifier
    {
        return $this->applicationWorld;
    }

    public function getSalutation(): ?Salutation
    {
        return $this->salutation;
    }

    public function getGivenName(): string
    {
        return $this->givenName;
    }

    public function getFamilyName(): string
    {
        return $this->familyName;
    }

    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }

    public function getWorksFor(): ?string
    {
        return $this->worksFor;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function getStreetAddress(): ?string
    {
        return $this->streetAddress;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function getAddressLocality(): ?string
    {
        return $this->addressLocality;
    }

    public function getAddressCountry(): ?string
    {
        return $this->addressCountry;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
