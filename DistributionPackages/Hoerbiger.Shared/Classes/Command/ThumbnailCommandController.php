<?php

declare(strict_types=1);

namespace Hoerbiger\Shared\Command;

/*
 * This script belongs to the "Hoerbiger.Shared" package.
 **/

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Cli\CommandController;
use Neos\Flow\ResourceManagement\PersistentResource;
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
    ) {
        parent::__construct();
    }

    public function clearDocumentThumbnailImagesCommand(): void
    {
        $documents = $this->documentRepository->findAll();
        $this->output->progressStart(count($documents));
        foreach ($this->documentRepository->findAll() as $document) {
            /** @var Document $document */
            /** @phpstan-ignore-next-line magic method */
            foreach ($this->thumbnailRepository->findByOriginalAsset($document) as $thumbnail) {
                /** @var Thumbnail $thumbnail */
                $this->thumbnailRepository->remove($thumbnail);
                /** @var ?PersistentResource $resource */
                $resource = $thumbnail->getResource();
                if (!$resource) {
                    continue;
                }
                $thumbnailImage = $this->imageRepository->findOneByResourceSha1($resource->getSha1());
                if ($thumbnailImage instanceof Image) {
                    $this->imageRepository->remove($thumbnailImage);
                }
            }
            $this->output->progressAdvance();
        }
        $this->output->progressFinish();
        $this->outputLine('');
    }
}
