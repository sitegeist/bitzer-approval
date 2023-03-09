<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Separator;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Hoerbiger\Shared\Presentation\Block\Separator\SeparatorVariant;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

/**
 * @Flow\Proxy(false)
 */
final class Separator extends AbstractComponentPresentationObject implements SeparatorInterface
{
    private SeparatorVariant $variant;

    private ?SlotInterface $content;

    public function __construct(
        SeparatorVariant $variant,
        ?SlotInterface $content
    ) {
        $this->variant = $variant;
        $this->content = $content;
    }

    public function getVariant(): SeparatorVariant
    {
        return $this->variant;
    }

    public function getContent(): ?SlotInterface
    {
        return $this->content;
    }
}
