<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Figure;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Media\Domain\Model\ThumbnailConfiguration;

#[Flow\Proxy(false)]
final class SourceSet
{
    /**
     * @var array<string,ThumbnailConfiguration>
     */
    private array $thumbnailConfigurations;

    /**
     * @param array<string,mixed> $array
     */
    public function __construct(array $array)
    {
        foreach ($array as $element) {
            if (!$element instanceof ThumbnailConfiguration) {
                throw new \InvalidArgumentException(self::class . ' can only consist of ' . ThumbnailConfiguration::class);
            }
        }

        ksort($array);
        $this->thumbnailConfigurations = $array;
    }

    public function getWidth(PresetIdentifier $presetIdentifier): string
    {
        return $this->thumbnailConfigurations[$presetIdentifier->value]->getWidth() . 'px';
    }

    public function toString(): string
    {
        return implode(', ', array_map(function (ThumbnailConfiguration $thumbnailConfiguration): string {
            return $thumbnailConfiguration->getWidth() . 'w';
        }, $this->thumbnailConfigurations));
    }

    public function getSrcset(): string
    {
        return $this->toString();
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
