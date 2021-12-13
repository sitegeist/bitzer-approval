<?php declare(strict_types=1);

namespace Hoerbiger\Shared\Aspect;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Domain\AssetSource\LanguageAwareAssetProxy;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Aop\JoinPointInterface;
use Neos\FluidAdaptor\View\AbstractTemplateView;
use Neos\Media\Browser\Controller\AssetController;
use Neos\Media\Browser\Domain\Session\BrowserState;
use Neos\Media\Domain\Model\Asset;
use Neos\Media\Domain\Repository\TagRepository;
use Neos\Utility\ObjectAccess;

/**
 * @Flow\Aspect
 * @Flow\Scope("singleton")
 */
final class AssetManagementAspect
{
    private BrowserState $browserState;

    private TagRepository $tagRepository;

    public function __construct(
        BrowserState $browserState,
        TagRepository $tagRepository
    ) {
        $this->browserState = $browserState;
        $this->tagRepository = $tagRepository;
    }

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
        $assetSourceIdentifier = $joinPoint->getMethodArgument('assetSourceIdentifier');
        $view = $this->getView($joinPoint);
    }

    private function getView(JoinPointInterface $joinPoint): AbstractTemplateView
    {
        /** @var AssetController $controller */
        $controller = $joinPoint->getProxy();

        return ObjectAccess::getProperty($controller, 'view', true);
    }
}
