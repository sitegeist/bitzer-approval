<?php declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\AffiliateDirectory\Application\Controller;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\View\ViewInterface;
use Neos\Flow\Security\Context as SecurityContext;
use Neos\Fusion\View\FusionView;
use Neos\Http\Factories\FlowUploadedFile;
use Neos\Neos\Controller\Module\AbstractModuleController;
use Neos\Error\Messages as Error;
use Neos\Neos\Fusion\Cache\ContentCacheFlusher;

final class AffiliateManagementController extends AbstractModuleController
{
    /**
     * @Flow\Inject
     * @var SecurityContext
     */
    protected $securityContext;

    /**
     * @Flow\Inject
     * @var ContentCacheFlusher
     */
    protected $contentCacheFlusher;

    /**
     * @Flow\InjectConfiguration(path="affiliateFinder.datasetPath")
     * @var string
     */
    protected $dataSetPath;

    /**
     * @var int
     */
    protected $maximumNumberOfUploads = 3;

    /**
     * @var string
     */
    protected $defaultViewObjectName = FusionView::class;

    public function indexAction(): void
    {
        $this->view->assignMultiple([
            'affiliateUploadForm' => $this->getActionUri('affiliateUploadForm')
        ]);
    }

    public function affiliateUploadFormAction(): void
    {
        $this->view->assignMultiple([
            'uploadAffiliateDataUri' => $this->getActionUri('uploadAffiliateData'),
            'csrfProtectionToken' => $this->securityContext->getCsrfProtectionToken()
        ]);
    }

    /**
     * @phpstan-param array<mixed,mixed> $command
     */
    public function uploadAffiliateDataAction(array $command): void
    {
        /** @var FlowUploadedFile $file */
        $file = $command['file'];

        $pathInfo = $file->getClientFilename() ? pathinfo($file->getClientFilename()) : [];
        if ($file->getStream()->getSize() > 0 &&
            isset($pathInfo['extension']) &&
            in_array($pathInfo['extension'], ['xlsx', 'xls'])
        ) {
            $this->saveUpload($file, $pathInfo['extension']);
            $this->addFlashMessage(
                'Datei mit wurde erfolgreich hochgeladen.'
            );

            $this->contentCacheFlusher->registerChangeOnNodeType(
                'Hoerbiger.HoerbigerCom.AffiliateDirectory:Document.AffiliateFinder'
            );
        } else {
            $this->addFlashMessage(
                'The uploaded file was empty or did not conform to the required format (xlsx or xls).
            Please try a new one.',
                "",
                Error\Message::SEVERITY_ERROR
            );
        }

        $this->redirect('affiliateUploadForm');
    }

    /**
     * @phpstan-param array<mixed,mixed> $parameters
     */
    private function getActionUri(string $actionName, array $parameters = []): string
    {
        return $this->controllerContext
            ->getUriBuilder()
            ->setCreateAbsoluteUri(true)
            ->uriFor(
                $actionName,
                $parameters,
                'AffiliateManagement',
                'Hoerbiger.HoerbigerCom.AffiliateDirectory',
                'Application'
            );
    }

    protected function initializeView(ViewInterface $view): void
    {
        parent::initializeView($view);
        /** @var FusionView $view */
        $view->setFusionPathPattern(
            'resource://Hoerbiger.HoerbigerCom.AffiliateDirectory/Private/Fusion/Integration/AffiliateManagement'
        );
        $view->assignMultiple([
            'flashMessages' => $this->getControllerContext()->getFlashMessageContainer()->getMessages()
        ]);

        $this->getControllerContext()->getFlashMessageContainer()->flush();
    }

    protected function saveUpload(FlowUploadedFile $file, string $uploadedExtension) :void
    {
        $targetPath = dirname($this->dataSetPath);
        $baseName = pathinfo($this->dataSetPath, PATHINFO_FILENAME);
        $now = new \DateTime();
        $extensions = ['xls', 'xlsx'];

        foreach ($extensions as $ext) {
            $fileToCheck = $targetPath . '/' . $baseName . '.' . $ext;
            if (file_exists($fileToCheck)) {
                // Archive the existing file
                rename(
                    $fileToCheck,
                    $targetPath . '/' . $baseName
                    . '_' . $now->format('Y-m-d-H-i')
                    . '.' . $ext
                );
            }
        }

        $targetFile = $targetPath . '/' . $baseName . '.' . $uploadedExtension;

        file_put_contents($targetFile, $file->getStream());

        $backupFiles = glob($targetPath . '/Affiliates_*');

        if ($backupFiles) {
            usort($backupFiles, function ($a, $b) {
                return filemtime($a) - filemtime($b);
            });

            if (count($backupFiles) >= $this->maximumNumberOfUploads) {
                unlink(array_shift($backupFiles));
            }
        }
    }
}
