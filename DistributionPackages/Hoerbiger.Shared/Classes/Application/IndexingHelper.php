<?php
namespace Hoerbiger\Shared\Application;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Domain\Asset\AssetAdapter;
use Hoerbiger\Shared\Domain\Asset\PdfContentExtractorInterface;
use Neos\ContentRepository\Domain\Model\Node;
use Neos\Flow\Annotations as Flow;
use Neos\Media\Domain\Model\Document;
use Neos\Eel\ProtectedContextAwareInterface;
use Neos\ContentRepository\Domain\Model\NodeInterface;

final class IndexingHelper implements ProtectedContextAwareInterface
{
    /**
     * @Flow\Inject
     * @var PdfContentExtractorInterface
     */
    protected $pdfContentExtractor;

    public function getPdfContent(?AssetAdapter $assetAdapter): string
    {
        if ($assetAdapter !== null) {
            $document = $assetAdapter->getAsset();
            if ($document instanceof Document) {
                return $this->pdfContentExtractor->extractContent($document);
            }
        }

        return '';
    }

    /**
     * @return mixed|\stdClass
     */
    public function indexJsonSerializable(?\JsonSerializable $jsonSerializable)
    {
        if ($jsonSerializable !== null) {
            return $jsonSerializable->jsonSerialize();
        }

        return new \stdClass;
    }

    /**
     * @param array<int,\JsonSerializable>|null $jsonSerializables
     * @return array<int,mixed|\stdClass>
     */
    public function indexArrayOfJsonSerializables(?array $jsonSerializables): array
    {
        if ($jsonSerializables !== null) {
            return array_map(function (\JsonSerializable $jsonSerializable) {
                return $this->indexJsonSerializable($jsonSerializable);
            }, $jsonSerializables);
        }

        return [];
    }

    public function getDateModified(NodeInterface $node): \DateTimeInterface
    {
        /** @var Node $node */
        if ($node->getProperty('datePublished') instanceof \DateTimeInterface) {
            return $node->getProperty('datePublished');
        }

        return $node->getLastModificationDateTime();
    }

    public function allowsCallOfMethod($methodName): bool
    {
        return true;
    }
}
