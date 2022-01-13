<?php declare(strict_types=1);

namespace Hoerbiger\Shared\Aspect;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Domain\AssetSource\LanguageAwareAssetProxy;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Aop\JoinPointInterface;
use Neos\Flow\Persistence\Doctrine\Query;
use Neos\FluidAdaptor\View\AbstractTemplateView;
use Neos\FluidAdaptor\View\TemplateView;
use Neos\Media\Browser\Controller\AssetController;
use Neos\Media\Browser\Domain\Session\BrowserState;
use Neos\Media\Domain\Model\Asset;
use Neos\Media\Domain\Model\AssetCollection;
use Neos\Media\Domain\Model\Tag;
use Neos\Media\Domain\Repository\AssetRepository;
use Neos\Media\Domain\Repository\TagRepository;
use Neos\Media\Domain\Service\AssetSourceService;
use Neos\Utility\ObjectAccess;

/**
 * @Flow\Aspect
 * @Flow\Scope("singleton")
 */
final class AssetManagementAspect
{
    /**
     * @Flow\Inject
     * @var BrowserState
     */
    protected $browserState;

    /**
     * @Flow\Inject
     * @var TagRepository
     */
    protected $tagRepository;

    /**
     * @Flow\Inject
     * @var AssetRepository
     */
    protected $assetRepository;

    /**
     * @Flow\Inject
     * @var AssetSourceService
     */
    protected $assetSourceService;

    /**
     * @Flow\Before("method(Neos\Media\Browser\Controller\AssetController->createAction())")
     */
    public function assignAssetSourceToNewlyCreatedAsset(JoinPointInterface $joinPoint): void
    {
        $this->assignAssetSourceToAsset($joinPoint->getMethodArgument('asset'));
    }

    /**
     * @Flow\Before("method(Neos\Media\Browser\Controller\AssetController->uploadAction())")
     */
    public function assignAssetSourceToNewlyUploadedAsset(JoinPointInterface $joinPoint): void
    {
        $this->assignAssetSourceToAsset($joinPoint->getMethodArgument('asset'));
    }

    private function assignAssetSourceToAsset(Asset $asset): void
    {
        if ($assetSourceIdentifier = $this->browserState->getActiveAssetSourceIdentifier()) {
            $asset->setAssetSourceIdentifier($assetSourceIdentifier);
        }
    }

    /**
     * @Flow\AfterReturning("method(Neos\Media\Browser\Controller\AssetController->editAction())")
     */
    public function assignTagsToEditView(JoinPointInterface $joinPoint): void
    {
        $view = $this->getView($joinPoint);

        $assetProxy = $view->getRenderingContext()->getVariableProvider()->get('assetProxy');

        if ($assetProxy instanceof LanguageAwareAssetProxy) {
            $asset = $assetProxy->getAsset();
            /** @var Asset $asset */
            $assetCollections = $asset->getAssetCollections();
            $tags = $assetCollections->count() > 0
                ? $this->tagRepository->findByAssetCollections($assetCollections->toArray())
                : $this->tagRepository->findAll();

            $view->assign('tags', $tags);
            if ($asset->getFileExtension() === 'pdf') {
                $view->assign('contentPreview', 'ContentPdf');
            }
        }
    }

    /**
     * @Flow\AfterReturning("method(Neos\Media\Browser\Controller\AssetController->indexAction())")
     */
    public function postProcessIndexViewVariables(JoinPointInterface $joinPoint): void
    {
        $assetSourceIdentifier = $this->browserState->getActiveAssetSourceIdentifier();
        $assetSources = $this->assetSourceService->getAssetSources();
        if ($assetSourceIdentifier && isset($assetSources[$assetSourceIdentifier])) {
            $assetSource = $assetSources[$assetSourceIdentifier];
            /** @var TemplateView $view */
            $view = $this->getView($joinPoint);
            $variables = $view->getRenderingContext()->getVariableProvider()->getAll();

            $assignedAssetCollections = $variables['assetCollections'] ?? [];
            array_walk($assignedAssetCollections, function (array &$item) use ($assetSourceIdentifier) {
                /** @var AssetCollection $assignedAssetCollection */
                $assignedAssetCollection = $item['object'];
                $countQuery = $this->assetRepository->createQuery();
                $countQuery = $countQuery->matching($countQuery->logicalAnd([
                    $countQuery->equals('assetSourceIdentifier', $assetSourceIdentifier),
                    $countQuery->contains('assetCollections', $assignedAssetCollection)
                ]));
                /** @var Query $countQuery */
                $countQuery->getQueryBuilder()->andWhere('e NOT INSTANCE OF Neos\Media\Domain\Model\ImageVariant');
                $item['count'] = $countQuery->count();
            });

            /** @var ?AssetCollection $assetCollection */
            $assetCollection = $joinPoint->getMethodArgument('assetCollection');

            $assignedTags = $variables['tags'] ?? [];
            array_walk(
                $assignedTags,
                function (array &$item) use ($assetSourceIdentifier, $assetCollection, $joinPoint) {
                    /** @var Tag $assignedTag */
                    $assignedTag = $item['object'];
                    $countQuery = $this->assetRepository->createQuery();
                    $countQuery = $countQuery->matching($countQuery->logicalAnd(array_filter([
                        $countQuery->equals('assetSourceIdentifier', $assetSourceIdentifier),
                        $countQuery->contains('tags', $assignedTag),
                        $assetCollection && $joinPoint->getMethodArgument('collectionMode') === 0
                            ? $countQuery->contains('assetCollections', $assetCollection)
                            : null
                    ])));
                    /** @var Query $countQuery */
                    $countQuery->getQueryBuilder()->andWhere('e NOT INSTANCE OF Neos\Media\Domain\Model\ImageVariant');
                    $item['count'] = $countQuery->count();
                }
            );

            $allCountQuery = $this->assetRepository->createQuery();
            $allCountQuery = $allCountQuery->matching($allCountQuery->logicalAnd(array_filter([
                $allCountQuery->equals('assetSourceIdentifier', $assetSourceIdentifier),
                $assetCollection && $joinPoint->getMethodArgument('collectionMode') === 0
                    ? $allCountQuery->contains('assetCollections', $assetCollection)
                    : null
            ])));
            /** @var Query $allCountQuery */
            $allCountQuery->getQueryBuilder()->andWhere('e NOT INSTANCE OF Neos\Media\Domain\Model\ImageVariant');

            $view->assignMultiple([
                'allCollectionsCount' => $assetSource->getAssetProxyRepository()->countAll(),
                'allCount' => $allCountQuery->count(),
                'assetCollections' => $assignedAssetCollections,
                'tags' => $assignedTags
            ]);
        }
    }

    private function getView(JoinPointInterface $joinPoint): AbstractTemplateView
    {
        /** @var AssetController $controller */
        $controller = $joinPoint->getProxy();

        return ObjectAccess::getProperty($controller, 'view', true);
    }
}
