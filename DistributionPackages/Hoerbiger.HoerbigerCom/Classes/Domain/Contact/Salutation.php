<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Domain\Contact;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

enum Salutation: string implements \JsonSerializable
{
    case VALUE_MR = 'Mr.';
    case VALUE_MS = 'Ms.';

    public function jsonSerialize(): string
    {
        return $this->value;
    }
}
