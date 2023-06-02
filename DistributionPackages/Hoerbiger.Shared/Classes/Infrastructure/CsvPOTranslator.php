<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Infrastructure;

/*
 * This script belongs to the "Hoerbiger.Shared" package.
 **/

use Neos\Flow\Annotations as Flow;
use Neos\Flow\I18n\Service as LocalizationService;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\ComponentName;
use Sitegeist\CsvPO\Domain\TranslationLabel;
use Sitegeist\CsvPO\Domain\TranslationLabelSource;
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

    /**
     * @return array<string,string>
     */
    public function getLabels(string $componentClassName): array
    {
        $labelSource = $this->getLabelSourceFor($componentClassName);
        $labels = [];

        foreach ($labelSource ? $labelSource->findAllTranslationLabels() : [] as $label) {
            $labels[$label->getIdentifier()] = $this->getTranslationForLabel($label);
        }
        return $labels;
    }

    public function getLabel(string $componentClassName, string $labelIdentifier): string
    {
        $labelSource = $this->getLabelSourceFor($componentClassName);
        if ($labelSource) {
            $label = $labelSource->findTranslationLabelByIdentifier($labelIdentifier);
            if ($label) {
                return $this->getTranslationForLabel($label);
            }
        }

        return $labelIdentifier;
    }
    private function getTranslationForLabel(TranslationLabel $label): string
    {
        return (string)$label->findTranslationForLocaleChain(
            $this->localizationService->getLocaleChain(
                $this->localizationService->getConfiguration()->getCurrentLocale()
            )
        ) ?: $label->getIdentifier();
    }

    private function getLabelSourceFor(string $componentClassName): ?TranslationLabelSource
    {
        $componentName = ComponentName::fromClassName($componentClassName);
        $labelSourcePath = \str_replace(
            '/Resources/',
            '/',
            $componentName->getFusionFilePath('resource://' . $componentName->packageKey)
            . '/' . $componentName->name . '.labels.csv'
        );

        return $this->labelSourceRepository->findOneByIdentifier($labelSourcePath);
    }
}
