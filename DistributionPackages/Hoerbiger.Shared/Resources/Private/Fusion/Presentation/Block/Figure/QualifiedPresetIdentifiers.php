<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Figure;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 * @implements \IteratorAggregate<string,PresetIdentifier>
 */
final class QualifiedPresetIdentifiers implements \IteratorAggregate, \Countable
{
    /**
     * @var array<string,PresetIdentifier>|PresetIdentifier[]
     */
    private array $presetIdentifiers;

    /**
     * @param array<string,PresetIdentifier> $array
     */
    public function __construct(array $array)
    {
        foreach ($array as $qualifier => $element) {
            if (empty($qualifier)) {
                throw new \InvalidArgumentException('Qualifiers must not be empty for members of ' . self::class);
            }
            if (!$element instanceof PresetIdentifier) {
                throw new \InvalidArgumentException(self::class . ' can only consist of ' . PresetIdentifier::class);
            }
        }
        $this->presetIdentifiers = $array;
    }

    /**
     * @return \ArrayIterator<string,PresetIdentifier>|PresetIdentifier[]
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->presetIdentifiers);
    }

    public function count(): int
    {
        return count($this->presetIdentifiers);
    }
}
