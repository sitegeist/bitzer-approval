<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Translation;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;

interface TranslationInterface extends ComponentPresentationObjectInterface
{
    public function getTranslationID(): string;
    public function getTranslationFile(): string;
}
