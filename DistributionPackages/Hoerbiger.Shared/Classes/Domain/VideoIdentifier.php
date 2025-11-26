<?php
namespace Hoerbiger\Shared\Domain;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use GuzzleHttp\Psr7\Uri;
use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
final class VideoIdentifier implements \JsonSerializable
{
    private function __construct(
        public readonly PlatformIdentifier $platformIdentifier,
        public readonly string $identifier,
        public readonly VideoAspectRatio $aspectRatio
    ) {
    }

    /**
     * @param array<string,string> $array
     */
    public static function fromArray(array $array): self
    {
        return new self(
            PlatformIdentifier::from($array['platformIdentifier']),
            $array['identifier'],
            VideoAspectRatio::from($array['aspectRatio'])
        );
    }

    public function renderUri(): Uri
    {
        return $this->platformIdentifier->renderUri($this->identifier);
    }

    /**
     * @return array<string,mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'platformIdentifier' => $this->platformIdentifier,
            'identifier' => $this->identifier,
            'aspectRatio' => $this->aspectRatio
        ];
    }
}
