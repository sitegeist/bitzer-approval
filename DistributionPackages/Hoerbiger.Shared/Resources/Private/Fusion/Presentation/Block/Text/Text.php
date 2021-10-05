<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Text;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Hoerbiger\Shared\Presentation\Block\Text\TextVariant;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

/**
 * @Flow\Proxy(false)
 */
final class Text extends AbstractComponentPresentationObject implements TextInterface
{
    private TextVariant $variant;

    private SlotInterface $content;

    private bool $canHaveColumns;

    public function __construct(
        TextVariant $variant,
        SlotInterface $content,
        bool $canHaveColumns
    ) {
        $this->variant = $variant;
        $this->content = $content;
        $this->canHaveColumns = $canHaveColumns;
    }

    public function getVariant(): TextVariant
    {
        return $this->variant;
    }

    public function getContent(): SlotInterface
    {
        return $this->content;
    }

    public function getCanHaveColumns(): bool
    {
        return $this->canHaveColumns;
    }
}
