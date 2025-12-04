<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Domain\Stuff;

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
final readonly class Thing implements \JsonSerializable
{
    public function __construct(
        public string $identifier,
        public string $content,
    ) {
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
