<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Breadcrumb;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use Hoerbiger\Shared\Presentation\Block\Icon\IconInterface;

/**
 * @Flow\Proxy(false)
 */
final class Breadcrumb extends AbstractComponentPresentationObject implements BreadcrumbInterface
{
    private SlotInterface $links;

    private SlotInterface $currentDocumentItem;

    private IconInterface $glue;

    public function __construct(
        SlotInterface $links,
        SlotInterface $currentDocumentItem,
        IconInterface $glue
    ) {
        $this->links = $links;
        $this->currentDocumentItem = $currentDocumentItem;
        $this->glue = $glue;
    }

    public function getLinks(): SlotInterface
    {
        return $this->links;
    }

    public function getCurrentDocumentItem(): SlotInterface
    {
        return $this->currentDocumentItem;
    }

    public function getGlue(): IconInterface
    {
        return $this->glue;
    }
}
