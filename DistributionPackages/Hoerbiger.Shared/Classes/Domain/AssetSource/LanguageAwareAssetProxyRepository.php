<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Domain\AssetSource;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Doctrine\ORM\EntityManagerInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\ObjectManagement\ObjectManagerInterface;
use Neos\Flow\Persistence\Doctrine\Query;
use Neos\Flow\Persistence\Doctrine\Repository;
use Neos\Flow\Persistence\Exception\InvalidQueryException;
use Neos\Flow\Persistence\QueryInterface;
use Neos\Media\Domain\Model\AssetCollection;
use Neos\Media\Domain\Model\AssetInterface;
use Neos\Media\Domain\Model\AssetSource\AssetProxy\AssetProxyInterface;
use Neos\Media\Domain\Model\AssetSource\AssetProxyQueryResultInterface;
use Neos\Media\Domain\Model\AssetSource\AssetProxyRepositoryInterface;
use Neos\Media\Domain\Model\AssetSource\AssetTypeFilter;
use Neos\Media\Domain\Model\AssetSource\SupportsCollectionsInterface;
use Neos\Media\Domain\Model\AssetSource\SupportsSortingInterface;
use Neos\Media\Domain\Model\AssetSource\SupportsTaggingInterface;
use Neos\Media\Domain\Model\Tag;
use Neos\Media\Domain\Repository\AssetRepository;
use Neos\Media\Domain\Repository\AudioRepository;
use Neos\Media\Domain\Repository\DocumentRepository;
use Neos\Media\Domain\Repository\ImageRepository;
use Neos\Media\Domain\Repository\VideoRepository;

final class LanguageAwareAssetProxyRepository implements
    AssetProxyRepositoryInterface,
    SupportsSortingInterface,
    SupportsCollectionsInterface,
    SupportsTaggingInterface
{
    /**
     * @Flow\Inject
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @Flow\Inject
     * @var EntityManagerInterface
     */
    protected $entityManager;

    private LanguageAwareAssetSource $assetSource;

    /**
     * @var AssetRepository
     */
    private $assetRepository;

    private string $assetTypeFilter = 'All';

    private ?AssetCollection $activeAssetCollection = null;

    /**
     * @var array<string,class-string>
     */
    private array $assetRepositoryClassNames = [
        'All' => AssetRepository::class,
        'Image' => ImageRepository::class,
        'Document' => DocumentRepository::class,
        'Video' => VideoRepository::class,
        'Audio' => AudioRepository::class
    ];

    public function __construct(LanguageAwareAssetSource $assetSource)
    {
        $this->assetSource = $assetSource;
    }

    public function initializeObject(): void
    {
        /** @var AssetRepository $assetRepository */
        $assetRepository = $this->objectManager->get($this->assetRepositoryClassNames[$this->assetTypeFilter]);

        $this->assetRepository = $assetRepository;
    }

    /**
     * Sets the property names to order results by. Expected like this:
     * array(
     *  'foo' => \Neos\Flow\Persistence\QueryInterface::ORDER_ASCENDING,
     *  'bar' => \Neos\Flow\Persistence\QueryInterface::ORDER_DESCENDING
     * )
     *
     * @param array<string,string> $orderings The property names to order by by default
     * @return void
     * @api
     */
    public function orderBy(array $orderings):void
    {
        $this->assetRepository->setDefaultOrderings($orderings);
    }

    public function filterByType(AssetTypeFilter $assetType = null): void
    {
        $this->assetTypeFilter = (string)$assetType ?: 'All';
        $this->initializeObject();
    }

    public function filterByCollection(AssetCollection $assetCollection = null): void
    {
        $this->activeAssetCollection = $assetCollection;
    }

    public function getAssetProxy(string $identifier): AssetProxyInterface
    {
        $asset = $this->assetRepository->findByIdentifier($identifier);
        if (!$asset instanceof AssetInterface) {
            throw new LanguageAwareAssetNotFound('The specified asset was not found.', 1509632861);
        }

        return new LanguageAwareAssetProxy($asset, $this->assetSource);
    }

    public function findAll(): LanguageAwareAssetProxyQueryResult
    {
        $queryResult = $this->assetRepository->findAll($this->activeAssetCollection);
        $query = $this->restrictQueryToAssetSource($queryResult->getQuery());
        $query = $this->filterOutImageVariants($query);

        return new LanguageAwareAssetProxyQueryResult($query->execute(), $this->assetSource);
    }

    public function findBySearchTerm(string $searchTerm): LanguageAwareAssetProxyQueryResult
    {
        $queryResult = $this->assetRepository->findBySearchTermOrTags($searchTerm, [], $this->activeAssetCollection);
        $query = $this->restrictQueryToAssetSource($queryResult->getQuery());
        $query = $this->filterOutImageVariants($query);

        return new LanguageAwareAssetProxyQueryResult($query->execute(), $this->assetSource);
    }

    public function findByTag(Tag $tag): LanguageAwareAssetProxyQueryResult
    {
        $queryResult = $this->assetRepository->findByTag($tag, $this->activeAssetCollection);
        $query = $this->restrictQueryToAssetSource($queryResult->getQuery());
        $query = $this->filterOutImageVariants($query);

        return new LanguageAwareAssetProxyQueryResult($query->execute(), $this->assetSource);
    }

    public function findUntagged(): LanguageAwareAssetProxyQueryResult
    {
        $queryResult = $this->assetRepository->findUntagged($this->activeAssetCollection);
        $query = $this->restrictQueryToAssetSource($queryResult->getQuery());
        $query = $this->filterOutImageVariants($query);

        return new LanguageAwareAssetProxyQueryResult($query->execute(), $this->assetSource);
    }

    public function countAll(): int
    {
        $query = $this->restrictQueryToAssetSource($this->assetRepository->createQuery());
        $query = $this->restrictQueryToAssetCollections($query);
        $query = $this->filterOutImageVariants($query);

        return $query->count();
    }

    public function countUntagged(): int
    {
        $query = $this->assetRepository->createQuery();
        try {
            $query->matching($query->isEmpty('tags'));
        } catch (InvalidQueryException $e) {
        }

        $query = $this->restrictQueryToAssetSource($query);
        $query = $this->restrictQueryToAssetCollections($query);
        $query = $this->filterOutImageVariants($query);

        return $query->count();
    }

    private function restrictQueryToAssetSource(QueryInterface $query): QueryInterface
    {
        $constraint = $query->getConstraint();
        $query->matching(
            $query->logicalAnd([
                $constraint,
                $query->equals('assetSourceIdentifier', $this->assetSource->getIdentifier())
            ])
        );

        return $query;
    }

    private function filterOutImageVariants(QueryInterface $query): QueryInterface
    {
        /** @var Query $query */
        $queryBuilder = $query->getQueryBuilder();
        $queryBuilder->andWhere('e NOT INSTANCE OF Neos\Media\Domain\Model\ImageVariant');

        return $query;
    }

    private function restrictQueryToAssetCollections(QueryInterface $query): QueryInterface
    {
        if ($this->activeAssetCollection === null) {
            return $query;
        }

        $constraints = $query->getConstraint();
        try {
            $query->matching(
                $query->logicalAnd([
                    $constraints,
                    $query->contains('assetCollections', $this->activeAssetCollection)
                ])
            );
        } catch (InvalidQueryException $e) {
        }

        return $query;
    }
}
