<?php declare(strict_types=1);

namespace Hoerbiger\Shared\Presentation\Block\Icon;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;

/**
 * @Flow\Proxy(false)
 */
final class Icon extends AbstractComponentPresentationObject implements IconInterface
{
    private string $collectionName;

    private string $iconName;

    private ?IconSize $size;

    private ?IconColor $color;

    public function __construct(
        string $collectionName,
        string $iconName,
        ?IconSize $size = null,
        ?IconColor $color = null
    )
    {
        $this->collectionName = $collectionName;
        $this->iconName = $iconName;
        $this->size = $size;
        $this->color = is_null($color) ? IconColor::current() : $color;
    }

    public static function fromStampedeDataSourceValue(
        string $dataSourceValue,
        ?IconSize $size = null,
        ?IconColor $color = null
    ): self {
        if (\mb_strpos($dataSourceValue, ':') !== false) {
            list($collectionName, $iconName) = explode(':', $dataSourceValue);
        } else {
            $collectionName = 'shared';
            $iconName = $dataSourceValue;
        }

        return new self(
            $collectionName,
            $iconName,
            $size,
            $color
        );
    }

    public function getCollectionName(): string
    {
        return $this->collectionName;
    }

    public function getIconName(): string
    {
        return $this->iconName;
    }

    public function getSize(): ?IconSize
    {
        return $this->size;
    }

    public function getColor(): ?IconColor
    {
        return $this->color;
    }
}
