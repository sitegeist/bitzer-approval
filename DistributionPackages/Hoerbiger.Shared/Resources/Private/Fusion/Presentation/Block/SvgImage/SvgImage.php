<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\SvgImage;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Psr\Http\Message\UriInterface;

/**
 * @Flow\Proxy(false)
 */
final class SvgImage extends AbstractComponentPresentationObject implements SvgImageInterface
{
    private UriInterface $source;

    private string $alt;

    private string $title;

    public function __construct(
        UriInterface $source,
        string $alt,
        string $title
    ) {
        $this->source = $source;
        $this->alt = $alt;
        $this->title = $title;
    }

    public function getSource(): UriInterface
    {
        return $this->source;
    }

    public function getAlt(): string
    {
        return $this->alt;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
