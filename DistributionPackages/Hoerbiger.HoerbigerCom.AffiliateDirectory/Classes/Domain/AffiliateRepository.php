<?php declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\AffiliateDirectory\Domain;

use Hoerbiger\HoerbigerCom\AffiliateDirectory\Presentation\Application\AffiliateFinder\Affiliate;
use Neos\Flow\Annotations as Flow;
use Hoerbiger\HoerbigerCom\AffiliateDirectory\Presentation\Application\AffiliateFinder\Affiliates;
use Hoerbiger\Shared\Application\CountryDirectory;
use Neos\Flow\I18n\Locale;
use Nezaniel\GeographicLibrary\Application\Service\GeoCodingService;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

/**
 *  @Flow\Scope("singleton")
 */
class AffiliateRepository
{
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
     * @Flow\InjectConfiguration(path="affiliateFinder.datasetPath")
     * @var string
     */
    protected $dataSetPath;

    public function getCountryLabel(string $countryCode, string $locale): string
    {
        return $this->countryDirectory->findCountryName($countryCode, new Locale($locale));
    }

    public function findAffiliates(string $locale): Affiliates
    {
        $dataSet = $this->getDataSet();

        $affiliates = [];

        foreach ($dataSet->getActiveSheet()->getRowIterator(10) as $row) {
            $legalName =
            $postalCode =
            $addressLocality =
            $addressCountry =
            $email =
            $telephone =
            $countryCode =
            $streetAddress = '';
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
                            $tags[] = $this->resolveTagFromSBUDivision($cell->getValue());
                            break;
                        case 'K':
                            $tags[] = $this->resolveTagFromLocationType($cell->getValue());
                            break;
                    }
                }
            }

            $completeAddress = $streetAddress . ' ' . $postalCode . ' ' . $addressLocality . ' ' . $countryCode;

            if (empty(trim($completeAddress))) {
                continue;
            }

            $coordinates = $this->geoCoder->fetchCoordinatesByAddress($completeAddress);
            if (is_null($coordinates)) {
                $coordinates = $this->geoCoder->fetchCoordinatesByAddress($legalName);
            }
            if (is_null($coordinates)) {
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

    public function getDataSet(): Spreadsheet
    {
        $reader = IOFactory::createReaderForFile(
            $this->dataSetPath
        );

        $reader->setReadDataOnly(true);

        return $reader->load($this->dataSetPath);
    }

    public function resolveTagFromSBUDivision(string $symbol): string
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

    public function resolveTagFromLocationType(string $symbol): string
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
}
