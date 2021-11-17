<?php

namespace Hoerbiger\Shared\Domain\Asset;

use Neos\Media\Domain\Model\Document;

/**
 * The interface to be implemented by PDF content extractors
 */
interface PdfContentExtractorInterface
{
    public function extractContent(Document $document): string;
}
