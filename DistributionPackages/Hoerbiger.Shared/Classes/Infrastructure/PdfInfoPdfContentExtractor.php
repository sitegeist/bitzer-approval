<?php declare(strict_types=1);

namespace Hoerbiger\Shared\Infrastructure;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Domain\Asset\PdfContentExtractorInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Media\Domain\Model\Document;

/**
 * The PDF content extractor infrastructure service
 *
 * @Flow\Scope("singleton")
 */
final class PdfInfoPdfContentExtractor implements PdfContentExtractorInterface
{
    public function extractContent(Document $document): string
    {
        if ($document->getMediaType() === 'application/pdf') {
            $sha1 = $document->getResource()->getSha1();
            /* @phpstan-ignore-next-line */
            $fileName = FLOW_PATH_DATA . 'Persistent/Resources/'
                . $sha1[0] . '/' . $sha1[1] . '/' . $sha1[2] . '/' . $sha1[3] . '/' . $sha1;

            $content = \shell_exec('timeout 2 pdftotext ' . $fileName . ' - 2> /dev/null');
            if ($content) {
                if (!\mb_detect_encoding($content, 'UTF-8', true)) {
                    $content = \utf8_encode($content);
                }
                return $content;
            }
        }

        return '';
    }
}
