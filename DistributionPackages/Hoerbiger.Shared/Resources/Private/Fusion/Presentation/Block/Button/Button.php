<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Button;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Hoerbiger\Shared\Presentation\Block\Button\ButtonType;
use Hoerbiger\Shared\Presentation\Block\Button\ButtonVariant;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use Psr\Http\Message\UriInterface;

#[Flow\Proxy(false)]
final class Button extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly ButtonType $type,
        public readonly ButtonVariant $variant,
        public readonly SlotInterface $content,
        public readonly ?UriInterface $href,
        public readonly ?string $target,
        public readonly ?string $rel
    ) {
    }
}
