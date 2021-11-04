<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Infrastructure;

/*
 * This script belongs to the "Hoerbiger.Shared" package.
 **/

use Neos\Flow\Annotations as Flow;
use Neos\Flow\I18n\Service as LocalizationService;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\ComponentName;
use Sitegeist\CsvPO\Domain\TranslationLabelSourceRepository;

/**
 * @Flow\Scope("singleton")
 */
final class CsvPOTranslator
{
    private TranslationLabelSourceRepository $labelSourceRepository;

    private LocalizationService $localizationService;

    public function __construct(
        TranslationLabelSourceRepository $labelSourceRepository,
        LocalizationService $localizationService
    ) {
        $this->labelSourceRepository = $labelSourceRepository;
        $this->localizationService = $localizationService;
    }

    public function getLabel(string $componentClassName, string $labelIdentifier): string
    {
        $componentName = ComponentName::fromClassName($componentClassName);
        $labelSourcePath = \str_replace(
            '/Resources/',
            '/',
            $componentName->getFusionFilePath('resource://' . $componentName->getPackageKey())
                . '/' . $componentName->getName() . '.labels.csv'
        );
        $labelSource = $this->labelSourceRepository->findOneByIdentifier($labelSourcePath);
        if ($labelSource) {
            $label = $labelSource->findTranslationLabelByIdentifier($labelIdentifier);
            if ($label) {
                return $label->findTranslationForLocaleChain($this->localizationService->getLocaleChain(
                    $this->localizationService->getConfiguration()->getCurrentLocale()
                ))->getTranslation() ?: $labelIdentifier;
            }
        }

        return $labelIdentifier;
    }
}
