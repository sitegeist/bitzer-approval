<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Domain\Contact;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
enum CustomerGroupIdentifier: string implements \JsonSerializable
{
    case IDENTIFIER_EXISTINGCUSTOMER = 'existingCustomer';
    case IDENTIFIER_NEWCUSTOMER = 'newCustomer';
    case IDENTIFIER_SUPPLIER = 'supplier';
    case IDENTIFIER_JOBCANDIDATE = 'jobCandidate';
    case IDENTIFIER_MEDIAREPRESENTATIVE = 'mediaRepresentative';

    public static function fromString(string $string): self|null
    {
        return match($string){
            'existingCustomer' => self::IDENTIFIER_EXISTINGCUSTOMER,
            'newCustomer' => self::IDENTIFIER_NEWCUSTOMER,
            'supplier' => self::IDENTIFIER_SUPPLIER,
            'jobCandidate' => self::IDENTIFIER_JOBCANDIDATE,
            'mediaRepresentative' => self::IDENTIFIER_MEDIAREPRESENTATIVE,
            default => null
        };
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }

}
