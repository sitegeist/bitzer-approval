<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Domain\Stuff;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
final class Thing implements \JsonSerializable
{
    private string $identifier;

    private string $content;

    public function __construct(string $identifier, string $content)
    {
        $this->identifier = $identifier;
        $this->content = $content;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return array<string,string>
     */
    public function jsonSerialize(): array
    {
        return [
            'identifier' => $this->identifier,
            'content' => $this->content
        ];
    }
}
