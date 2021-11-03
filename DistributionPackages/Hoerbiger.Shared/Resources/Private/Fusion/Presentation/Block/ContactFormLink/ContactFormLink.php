<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\ContactFormLink;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use Hoerbiger\Shared\Presentation\Block\Button\ButtonInterface;

/**
 * @Flow\Proxy(false)
 */
final class ContactFormLink extends AbstractComponentPresentationObject implements ContactFormLinkInterface
{
    private SlotInterface $content;

    private ButtonInterface $button;

    public function __construct(
        SlotInterface $content,
        ButtonInterface $button
    ) {
        $this->content = $content;
        $this->button = $button;
    }

    public function getContent(): SlotInterface
    {
        return $this->content;
    }

    public function getButton(): ButtonInterface
    {
        return $this->button;
    }
}
