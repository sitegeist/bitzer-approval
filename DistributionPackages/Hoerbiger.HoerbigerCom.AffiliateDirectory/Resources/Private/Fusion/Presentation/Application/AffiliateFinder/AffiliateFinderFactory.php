<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\AffiliateDirectory\Presentation\Application\AffiliateFinder;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.AffiliateDirectory package.
 */

use Hoerbiger\HoerbigerCom\AffiliateDirectory\Domain\AffiliateRepository;
use Neos\Flow\I18n\Locale;
use Neos\Flow\I18n\Service as LocalizationService;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use Neos\Flow\Annotations as Flow;
use Sitegeist\CsvPO\Domain\TranslationLabelSourceRepository;

final class AffiliateFinderFactory extends AbstractComponentPresentationObjectFactory
{

    /**
     * @Flow\Inject
     * @var TranslationLabelSourceRepository
     */
    protected $translationLabelSourceRepository;

    /**
     * @Flow\Inject
     * @var AffiliateRepository
     */
    protected $affiliateRepository;

    /**
     * @Flow\Inject
     * @var LocalizationService
     */
    protected $localizationService;

    public function create(string $locale): AffiliateFinderInterface
    {
        return new AffiliateFinder(
            $this->affiliateRepository->findAffiliates($locale),
            'AIzaSyDu4LZZnG6K5U5Yx-BrkLfLD4jxIi474aM',
            $this->getLabels($locale)
        );
    }

    /**
     * @return array<string,string>
     */
    private function getLabels(string $language): array
    {
        $locale = new Locale($language);
        $labels = [];
        $labelSource = $this->translationLabelSourceRepository->findOneByIdentifier(
            'resource://Hoerbiger.HoerbigerCom.AffiliateDirectory/Private/Fusion/Presentation/Application/AffiliateFinder/AffiliateFinder.labels.csv'
        );
        foreach ($labelSource ? $labelSource->findAllTranslationLabels() : [] as $label) {
            $labels[$label->getIdentifier()] = (string)$label->findTranslationForLocaleChain(
                $this->localizationService->getLocaleChain($locale)
            ) ?: $label->getIdentifier();
        }

        return $labels;
    }
}
