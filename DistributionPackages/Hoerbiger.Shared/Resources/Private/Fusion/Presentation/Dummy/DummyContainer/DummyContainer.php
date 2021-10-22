<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Dummy\DummyContainer;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Psr\Http\Message\UriInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

/**
 * @Flow\Proxy(false)
 */
final class DummyContainer extends AbstractComponentPresentationObject implements DummyContainerInterface
{
    private UriInterface $sampleImageUri;

    private SlotInterface $content;

    public function __construct(
        UriInterface $sampleImageUri,
        SlotInterface $content
    ) {
        $this->sampleImageUri = $sampleImageUri;
        $this->content = $content;
    }

    public function getSampleImageUri(): UriInterface
    {
        return $this->sampleImageUri;
    }

    public function getContent(): SlotInterface
    {
        return $this->content;
    }
}
