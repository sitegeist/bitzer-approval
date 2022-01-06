<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Command;

/*
 * This script belongs to the "Hoerbiger.Shared" package.
 **/

use Hoerbiger\Shared\Domain\Asset\PdfContentExtractorInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Cli\CommandController;
use Neos\Media\Domain\Model\Document;
use Neos\Media\Domain\Repository\DocumentRepository;

/**
 * A command controller for integrity tests
 */
final class IntegrityCommandController extends CommandController
{
    /**
     * @Flow\Inject
     * @var DocumentRepository
     */
    protected $documentRepository;

    /**
     * @Flow\Inject
     * @var PdfContentExtractorInterface
     */
    protected $pdfContentExtractor;

    public function testPdfContentExtractionCommand(string $documentIdentifier): void
    {
        $document = $this->documentRepository->findByIdentifier($documentIdentifier);
        if ($document instanceof Document) {
            $this->outputLine($this->pdfContentExtractor->extractContent($document));
        } else {
            $this->outputLine('No document found for identifier ' . $documentIdentifier);
        }
    }
}
