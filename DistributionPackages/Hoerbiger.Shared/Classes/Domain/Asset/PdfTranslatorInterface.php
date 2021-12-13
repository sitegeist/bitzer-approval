<?php declare(strict_types=1);

namespace Hoerbiger\Shared\Domain\Asset;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Media\Domain\Model\Document;

/**
 * The interface for PDF translators
 */
interface PdfTranslatorInterface
{
    public function translateToCreativeWork(Document $document): CreativeWork;
}
