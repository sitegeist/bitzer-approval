<?php declare(strict_types=1);

namespace Hoerbiger\Shared\Infrastructure;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Domain\Asset\AssetMetadataRepository;
use Hoerbiger\Shared\Domain\Asset\CreativeWork;
use Hoerbiger\Shared\Domain\Asset\PdfTranslatorInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Media\Domain\Model\Document;
use Neos\Media\Domain\Model\Tag;

/**
 * The PDF translator
 *
 * @Flow\Scope("singleton")
 */
final class PdfTranslator implements PdfTranslatorInterface
{
    private AssetMetadataRepository $assetMetadataRepository;

    public function __construct(
        AssetMetadataRepository $assetMetadataRepository
    ) {
        $this->assetMetadataRepository = $assetMetadataRepository;
    }

    public function translateToCreativeWork(Document $document): CreativeWork
    {
        $metadata = $this->assetMetadataRepository->findForAsset($document);

        return new CreativeWork(
            $document->getCaption(),
            $document->getCopyrightNotice(),
            \DateTimeImmutable::createFromMutable($document->getLastModified()),
            $metadata->getDatePublished(),
            $document->getTitle() ?: $document->getResource()->getFilename(),
            array_map(function (Tag $tag) {
                return $tag->getLabel();
            }, $document->getTags()->toArray()),
            $document->getCaption(),
            $document->getLabel(),
            $document->getCaption()
        );
    }
}
