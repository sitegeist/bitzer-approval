<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Slidable\ThumbNavigationItem;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureInterface;

/**
 * @Flow\Proxy(false)
 */
final class ThumbNavigationItem extends AbstractComponentPresentationObject implements ThumbNavigationItemInterface
{
    private ?FigureInterface $figure;

    private string $label;

    public function __construct(
        ?FigureInterface $figure,
        string $label
    ) {
        $this->figure = $figure;
        $this->label = $label;
    }

    public function getFigure(): ?FigureInterface
    {
        return $this->figure;
    }

    public function getLabel(): string
    {
        return $this->label;
    }
}
