<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\PartialFigureEclipse;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Hoerbiger\Shared\Presentation\Block\PartialFigureEclipse\PartialFigureEclipseVariant;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureInterface;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineInterface;
use Hoerbiger\Shared\Presentation\Block\Text\TextInterface;
use Hoerbiger\Shared\Presentation\Block\Link\LinkInterface;

/**
 * @Flow\Proxy(false)
 */
final class PartialFigureEclipse extends AbstractComponentPresentationObject implements PartialFigureEclipseInterface
{
    private PartialFigureEclipseVariant $variant;

    private FigureInterface $figure;

    private ?HeadlineInterface $headline;

    private TextInterface $text;

    private ?LinkInterface $link;

    public function __construct(
        PartialFigureEclipseVariant $variant,
        FigureInterface $figure,
        ?HeadlineInterface $headline,
        TextInterface $text,
        ?LinkInterface $link
    ) {
        $this->variant = $variant;
        $this->figure = $figure;
        $this->headline = $headline;
        $this->text = $text;
        $this->link = $link;
    }

    public function getVariant(): PartialFigureEclipseVariant
    {
        return $this->variant;
    }

    public function getFigure(): FigureInterface
    {
        return $this->figure;
    }

    public function getHeadline(): ?HeadlineInterface
    {
        return $this->headline;
    }

    public function getText(): TextInterface
    {
        return $this->text;
    }

    public function getLink(): ?LinkInterface
    {
        return $this->link;
    }
}
