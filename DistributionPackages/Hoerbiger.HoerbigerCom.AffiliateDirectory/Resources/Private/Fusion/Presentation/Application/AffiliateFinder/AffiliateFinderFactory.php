<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\AffiliateDirectory\Presentation\Application\AffiliateFinder;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.AffiliateDirectory package.
 */

use Hoerbiger\Shared\Application\CountryDirectory;
use Neos\Flow\I18n\Locale;
use Neos\Flow\I18n\Service as LocalizationService;
use Nezaniel\GeographicLibrary\Application\Service\GeoCodingService;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Neos\Flow\Annotations as Flow;
use Sitegeist\CsvPO\Domain\TranslationLabelSourceRepository;

final class AffiliateFinderFactory extends AbstractComponentPresentationObjectFactory
{
    /**
     * @Flow\InjectConfiguration(path="affiliateFinder.datasetPath")
     * @var string
     */
    protected $dataSetPath;

    /**
     * @Flow\Inject
     * @var CountryDirectory
     */
    protected $countryDirectory;

    /**
     * @Flow\Inject
     * @var GeoCodingService
     */
    protected $geoCoder;

    /**
     * @Flow\Inject
     * @var TranslationLabelSourceRepository
     */
    protected $translationLabelSourceRepository;

    /**
     * @Flow\Inject
     * @var LocalizationService
     */
    protected $localizationService;

    public function create(string $locale): AffiliateFinderInterface
    {
        return new AffiliateFinder(
            $this->findAffiliates($locale),
            'AIzaSyDu4LZZnG6K5U5Yx-BrkLfLD4jxIi474aM',
            $this->getLabels($locale)
        );
    }

    private function findAffiliates(string $locale): Affiliates
    {
        $dataSet = $this->getDataSet();

        $affiliates = [];

        foreach ($dataSet->getActiveSheet()->getRowIterator(10) as $row) {
            $legalName = $postalCode = $addressLocality = $addressCountry = $email = $telephone = $latitude = $countryCode = $streetAddress = '';
            $tags = [];
            foreach ($row->getCellIterator('G') as $columnLabel => $cell) {
                if (!is_null($cell) && !is_null($cell->getValue())) {
                    switch ($columnLabel) {
                        case 'M':
                            $legalName = $cell->getValue();
                            break;
                        case 'L':
                            $postalCode = (string)$cell->getValue();
                            break;
                        case 'N':
                            $streetAddress = $cell->getValue();
                            break;
                        case 'O':
                            $addressLocality = $cell->getValue();
                            break;
                        case 'G':
                            $addressCountry = $this->getCountryLabel($cell->getValue(), $locale);
                            $countryCode = $cell->getValue();
                            break;
                        case 'P':
                            $email = $cell->getValue();
                            break;
                        case 'Q':
                            $telephone = $cell->getValue();
                            break;
                        case 'J':
                            $tags[] = $this->resolveTagFromSBUDevision($cell->getValue());
                            break;
                        case 'K':
                            $tags[] = $this->resolveTagFromLocationType($cell->getValue());
                            break;
                    }

                }
            }

            $completeAddress = $streetAddress . ' ' . $postalCode .' '. $addressLocality . ' ' . $countryCode;

            if (empty(trim($completeAddress))) {
                continue;
            }

            $coordinates =  $this->geoCoder->fetchCoordinatesByAddress($completeAddress);

            if(is_null($coordinates)) {
                continue;
            }

            $affiliates[] = new Affiliate(
                $legalName,
                $streetAddress,
                $postalCode,
                $addressLocality,
                $addressCountry,
                $email,
                $telephone,
                $coordinates->getLatitude(),
                $coordinates->getLongitude(),
                $tags
            );
        }

        return new Affiliates($affiliates);
    }

    private function getCountryLabel(string $countryCode, string $locale): string
    {
        return $this->countryDirectory->findCountryName($countryCode, new Locale($locale));
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

    private function resolveTagFromSBUDevision(string $symbol): string
    {
        switch ($symbol) {
            case 'C':
                return 'Compression Technology';
            case 'D':
                return 'Drive Technology';
            case 'E':
                return 'Engine';
            case 'H':
                return 'Automotive Hydraulics';
            case 'HH':
                return 'HOERBIGER Holding';
            case 'R':
                return 'Rotary Solutions';
            case 'S':
                return 'Safety';
            default:
                return '';
        }
    }

    private function resolveTagFromLocationType(string $symbol): string
    {
        switch ($symbol) {
            case 'IO':
                return 'Compression Technology';
            case 'LP':
                return 'Drive Technology';
            case 'S':
                return 'Engine';
            case 'SP':
                return 'Automotive Hydraulics';
            default:
                return '';
        }
    }

    private function getDataSet(): Spreadsheet
    {
        $reader = IOFactory::createReaderForFile(
            $this->dataSetPath
        );

        $reader->setReadDataOnly(true);

        return $reader->load($this->dataSetPath);
    }
}
