<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Translation;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;

/**
 * @Flow\Proxy(false)
 */
final class Translation extends AbstractComponentPresentationObject implements TranslationInterface
{
    private string $translationID;
    private string $translationFile;


    public function __construct(
        string $translationID,
        string $translationFile

    ) {
        $this->translationID = $translationID;
        $this->translationFile = $translationFile;
    }

    public function getTranslationID(): string
    {
        return $this->translationID;
    }

    public function getTranslationFile(): string
    {
        return $this->translationFile;
    }
}
