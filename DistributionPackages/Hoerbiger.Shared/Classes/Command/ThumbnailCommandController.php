<?php

declare(strict_types=1);

namespace Hoerbiger\Shared\Command;

/*
 * This script belongs to the "Hoerbiger.Shared" package.
 **/

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Cli\CommandController;
use Neos\Media\Domain\Model\Document;
use Neos\Media\Domain\Model\Image;
use Neos\Media\Domain\Model\Thumbnail;
use Neos\Media\Domain\Repository\DocumentRepository;
use Neos\Media\Domain\Repository\ImageRepository;
use Neos\Media\Domain\Repository\ThumbnailRepository;

/**
 * A command controller for thumbnail handling
 */
final class ThumbnailCommandController extends CommandController
{
    public function __construct(
        private readonly DocumentRepository $documentRepository,
        private readonly ThumbnailRepository $thumbnailRepository,
        private readonly ImageRepository $imageRepository,
    )
    {
        parent::__construct();
    }

    public function clearDocumentThumbnailImagesCommand(): void
    {
        foreach ($this->documentRepository->findAll() as $document) {
            /** @var Document $document */
            foreach ($this->thumbnailRepository->findByAsset($document) as $thumbnail) {
                /** @var Thumbnail $thumbnail */
                $thumbnailImage = $this->imageRepository->findOneByResourceSha1($thumbnail->getResource()->getSha1());
                if ($thumbnailImage instanceof Image) {
                    $this->imageRepository->remove($thumbnailImage);
                }
            }
        }
    }
}
