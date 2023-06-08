<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Domain;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
final class Anchor
{
    private string $identifier;

    private string $label;

    public function __construct(string $identifier, string $label)
    {
        $this->identifier = $identifier;
        $this->label = $label;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return array<string,string>
     */
    public function jsonSerialize(): array
    {
        return [
            'identifier' => $this->identifier,
            'label' => $this->label
        ];
    }
}
