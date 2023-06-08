<?php declare(strict_types=1);

namespace Hoerbiger\Shared\Presentation\Block\Icon;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;

#[Flow\Proxy(false)]
final class Icon extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly string $collectionName,
        public readonly string $iconName,
        public readonly ?IconSize $size = null,
        public readonly ?IconColor $color = null
    )
    {
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
}
