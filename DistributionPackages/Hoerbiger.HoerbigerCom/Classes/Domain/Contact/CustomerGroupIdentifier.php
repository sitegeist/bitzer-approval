<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Domain\Contact;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

enum CustomerGroupIdentifier: string implements \JsonSerializable
{
    case IDENTIFIER_EXISTINGCUSTOMER = 'existingCustomer';
    case IDENTIFIER_NEWCUSTOMER = 'newCustomer';
    case IDENTIFIER_SUPPLIER = 'supplier';
    case IDENTIFIER_JOBCANDIDATE = 'jobCandidate';
    case IDENTIFIER_MEDIAREPRESENTATIVE = 'mediaRepresentative';

    public function jsonSerialize(): string
    {
        return $this->value;
    }
}
