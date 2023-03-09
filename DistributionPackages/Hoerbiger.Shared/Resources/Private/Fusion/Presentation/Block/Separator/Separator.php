<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Separator;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Hoerbiger\Shared\Presentation\Block\Separator\SeparatorVariant;

/**
 * @Flow\Proxy(false)
 */
final class Separator extends AbstractComponentPresentationObject implements SeparatorInterface
{
    private SeparatorVariant $variant;

    public function __construct(
        SeparatorVariant $variant
    ) {
        $this->variant = $variant;
    }

    public function getVariant(): SeparatorVariant
    {
        return $this->variant;
    }
}
