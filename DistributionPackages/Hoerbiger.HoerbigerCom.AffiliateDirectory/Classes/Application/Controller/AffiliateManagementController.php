<?php declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\AffiliateDirectory\Application\Controller;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\View\ViewInterface;
use Neos\Flow\Security\Context as SecurityContext;
use Neos\Fusion\View\FusionView;
use Neos\Http\Factories\FlowUploadedFile;
use Neos\Neos\Controller\Module\AbstractModuleController;
use Neos\Error\Messages as Error;

final class AffiliateManagementController extends AbstractModuleController
{
    /**
     * @Flow\Inject
     * @var SecurityContext
     */
    protected $securityContext;

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

        /** @phpstan-ignore-next-line */
        $pathInfo = $file->getClientFilename() ? pathinfo($file->getClientFilename()) : [];
        if ($file->getStream()->getSize() > 0 &&
            isset($pathInfo['extension']) &&
            $pathInfo['extension'] === 'xls'
        ) {
            $this->saveUpload($file);
            $this->addFlashMessage(
                'Datei mit wurde erfolgreich hochgeladen.'
            );
        } else {
            $this->addFlashMessage(
                'Die hochgeladene Datei war leer oder nicht im geforderten Format (xls).
            Bitte prüfen und erneut hochladen.',
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

    protected function saveUpload(FlowUploadedFile $file) :void
    {
        $targetFile = $this->dataSetPath;
        $targetPath = dirname($targetFile);
        $now = new \DateTime();
        rename(
            $targetFile,
            $targetPath . '/' . basename($targetFile, '.xls') . '_' . $now->format('Y-m-d-H-i')  . '.xls'
        );
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
