<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Video;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureInterface;

/**
 * @Flow\Proxy(false)
 */
final class Video extends AbstractComponentPresentationObject implements VideoInterface
{
    private string $source;

    private ?FigureInterface $coverImage;

    public function __construct(
        string $source,
        ?FigureInterface $coverImage
    ) {
        $this->source = $source;
        $this->coverImage = $coverImage;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function getCoverImage(): ?FigureInterface
    {
        return $this->coverImage;
    }
}
