<?php declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Domain\Stuff;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
final class Stuff
{
    public function findThingsByQuery(StuffQuery $query): StuffResponse
    {
        return new StuffResponse();
    }
}
