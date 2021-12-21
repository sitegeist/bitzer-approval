<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Video;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

/**
 * @Flow\Proxy(false)
 */
final class Video extends AbstractComponentPresentationObject implements VideoInterface
{
    private string $source;

    private ?FigureInterface $figure;

    private ?SlotInterface $caption;

    public function __construct(
        string $source,
        ?FigureInterface $figure,
        ?SlotInterface $caption
    ) {
        $this->source = $source;
        $this->figure = $figure;
        $this->caption = $caption;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function getFigure(): ?FigureInterface
    {
        return $this->figure;
    }

    public function getCaption(): ?SlotInterface
    {
        return $this->caption;
    }
}
