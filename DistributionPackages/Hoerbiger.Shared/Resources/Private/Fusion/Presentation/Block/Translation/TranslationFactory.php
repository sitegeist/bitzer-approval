<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Translation;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;

final class TranslationFactory extends AbstractComponentPresentationObjectFactory
{
    public function __invoke(string $translationID, string $translationFile): Translation
    {
        return new Translation($translationID,$translationFile);
    }
}
