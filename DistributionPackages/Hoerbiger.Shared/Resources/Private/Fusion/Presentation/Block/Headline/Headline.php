<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Headline;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineVariant;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineType;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

/**
 * @Flow\Proxy(false)
 */
final class Headline extends AbstractComponentPresentationObject implements HeadlineInterface
{
    private HeadlineVariant $variant;

    private HeadlineType $type;

    private SlotInterface $content;

    public function __construct(
        HeadlineVariant $variant,
        HeadlineType $type,
        SlotInterface $content
    ) {
        $this->variant = $variant;
        $this->type = $type;
        $this->content = $content;
    }

    public function getVariant(): HeadlineVariant
    {
        return $this->variant;
    }

    public function getType(): HeadlineType
    {
        return $this->type;
    }

    public function getContent(): SlotInterface
    {
        return $this->content;
    }
}
