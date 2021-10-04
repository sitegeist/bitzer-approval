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

    public function __construct(
        string $collectionName,
        string $iconName
    ) {
        $this->collectionName = $collectionName;
        $this->iconName = $iconName;
    }

    public function getCollectionName(): string
    {
        return $this->collectionName;
    }

    public function getIconName(): string
    {
        return $this->iconName;
    }
}
