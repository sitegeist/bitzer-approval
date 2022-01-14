<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\PeopleStory;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\Block\Figure\FigureInterface;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineInterface;
use Hoerbiger\Shared\Presentation\Block\Text\TextInterface;
use Hoerbiger\Shared\Presentation\Block\Quotation\QuotationInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

/**
 * @Flow\Proxy(false)
 */
final class PeopleStory extends AbstractComponentPresentationObject implements PeopleStoryInterface
{
    private FigureInterface $figure;

    private HeadlineInterface $headline;

    private ?TextInterface $text;

    private ?QuotationInterface $quotation;

    private ?SlotInterface $metaline;

    private ?SlotInterface $tags;

    public function __construct(
        FigureInterface $figure,
        HeadlineInterface $headline,
        ?TextInterface $text,
        ?QuotationInterface $quotation,
        ?SlotInterface $metaline,
        ?SlotInterface $tags
    ) {
        $this->figure = $figure;
        $this->headline = $headline;
        $this->text = $text;
        $this->quotation = $quotation;
        $this->metaline = $metaline;
        $this->tags = $tags;
    }

    public function getFigure(): FigureInterface
    {
        return $this->figure;
    }

    public function getHeadline(): HeadlineInterface
    {
        return $this->headline;
    }

    public function getText(): ?TextInterface
    {
        return $this->text;
    }

    public function getQuotation(): ?QuotationInterface
    {
        return $this->quotation;
    }

    public function getMetaline(): ?SlotInterface
    {
        return $this->metaline;
    }

    public function getTags(): ?SlotInterface
    {
        return $this->tags;
    }
}
