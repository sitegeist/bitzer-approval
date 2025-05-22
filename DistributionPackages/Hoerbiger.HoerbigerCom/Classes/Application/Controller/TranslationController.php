<?php declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Application\Controller;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use Hoerbiger\HoerbigerCom\Domain\Translation\TranslationExport;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Controller\ActionController;
use Neos\Utility\Files;

/**
 * @Flow\Scope("singleton")
 */
class TranslationController extends ActionController
{
    #[Flow\Inject]
    protected TranslationExport $translationExport;

    /**
     * @var array<int,string>
     */
    protected $supportedMediaTypes = ['application/json'];

    /**
     * @var array<string,string>
     */
    protected $viewFormatToObjectNameMap = ['json' => 'Neos\Flow\Mvc\View\JsonView'];

    public function exportDocumentAction(string $documentIdentifier, string $language): void
    {
        ob_clean();
        $exportFile = $this->translationExport->forDocument($documentIdentifier, $language);

        if (!$exportFile) {
            return;
        }

        $filePath = $exportFile->persist($language);
        $fileName = $exportFile->getFileName();

        if (!$fileName) {
            return;
        }

        $content = Files::getFileContents($filePath);

        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header(
            "Content-Disposition: attachment; filename="
            . $fileName
        );
        header("Content-Length: " . strlen($content));
        echo($content);
        $exportFile->remove();
        exit();
    }
}
