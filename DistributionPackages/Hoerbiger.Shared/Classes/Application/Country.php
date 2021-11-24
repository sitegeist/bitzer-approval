<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Application;

/*
 * This file is part of the Hoerbiger.Shared package.
 */


final class Country
{
    private string $code;

    private string $name;

    public function __construct(string $code, string $name)
    {
        $this->code = $code;
        $this->name = $name;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
