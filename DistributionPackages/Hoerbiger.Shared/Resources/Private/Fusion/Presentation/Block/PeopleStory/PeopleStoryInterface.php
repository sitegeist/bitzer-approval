<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\PeopleStory;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\Block\Figure\FigureInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineInterface;
use Hoerbiger\Shared\Presentation\Block\Text\TextInterface;
use Hoerbiger\Shared\Presentation\Block\Quotation\QuotationInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

interface PeopleStoryInterface extends ComponentPresentationObjectInterface
{
    public function getFigure(): FigureInterface;

    public function getHeadline(): HeadlineInterface;

    public function getText(): ?TextInterface;

    public function getQuotation(): ?QuotationInterface;

    public function getMetaline(): ?SlotInterface;

    public function getTags(): ?SlotInterface;
}
