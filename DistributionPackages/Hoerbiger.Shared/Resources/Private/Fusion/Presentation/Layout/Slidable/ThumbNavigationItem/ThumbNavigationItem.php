<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Slidable\ThumbNavigationItem;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureInterface;
use Hoerbiger\Shared\Presentation\Layout\Slidable\ThumbNavigationItem\ThumbNavigationItemType;

/**
 * @Flow\Proxy(false)
 */
final class ThumbNavigationItem extends AbstractComponentPresentationObject implements ThumbNavigationItemInterface
{
    private ?FigureInterface $figure;

    private string $label;

    private  ?ThumbNavigationItemType $type;

    public function __construct(
        ?FigureInterface $figure,
        string $label,
        ?ThumbNavigationItemType $type
    ) {
        $this->figure = $figure;
        $this->label = $label;
        $this->type = $type;
    }

    public function getFigure(): ?FigureInterface
    {
        return $this->figure;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getType(): ?ThumbNavigationItemType
    {
        return $this->type;
    }


}
