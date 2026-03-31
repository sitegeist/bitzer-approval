<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\AffiliateDirectory\Domain;

use Hoerbiger\HoerbigerCom\AffiliateDirectory\Presentation\Application\AffiliateFinder\Affiliate;
use Neos\Flow\Annotations as Flow;
use Hoerbiger\HoerbigerCom\AffiliateDirectory\Presentation\Application\AffiliateFinder\Affiliates;
use Nezaniel\GeographicLibrary\Application\Service\GeoCodingService;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

/**
 *  @Flow\Scope("singleton")
 */
class AffiliateRepository
{
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

    public function findAffiliates(string $locale): Affiliates
    {
        $dataSet = $this->getDataSet();

        $affiliates = [];
        $columnsByKey = [];
        foreach ($dataSet->getActiveSheet()->getRowIterator(11)->current()->getCellIterator() as $column => $cell) {
            if ($cell instanceof Cell) {
                $columnsByKey[$cell->getValue()] = $column;
            }
        }
        $countryLabelKey = ($locale === 'de' ? 'country_label_DE' : 'country_label_EN');

        foreach ($dataSet->getActiveSheet()->getRowIterator(12) as $row) {
            $legalNameCell = $row->getCellIterator($columnsByKey['company'])->current();
            $postalCodeCell = $row->getCellIterator($columnsByKey['zipcode'])->current();
            $addressLocalityCell = $row->getCellIterator($columnsByKey['city'])->current();
            $addressCountryCell = $row->getCellIterator($columnsByKey[$countryLabelKey])->current();
            $emailCell = $row->getCellIterator($columnsByKey['email'])->current();
            $telephoneCell = $row->getCellIterator($columnsByKey['phone'])->current();
            $countryCodeCell = $row->getCellIterator($columnsByKey['countries_id'])->current();
            $streetAddressCell = $row->getCellIterator($columnsByKey['street'])->current();

            if (is_null($legalNameCell) || is_null($streetAddressCell) || is_null($postalCodeCell)
                || is_null($addressLocalityCell) || is_null($addressCountryCell) || is_null($emailCell)
                || is_null($telephoneCell)) {
                continue;
            }

            $completeAddress = $streetAddressCell . ' ' . $postalCodeCell
                . ' ' . $addressLocalityCell . ' ' . $countryCodeCell;

            if (empty(trim($completeAddress))) {
                continue;
            }

            $coordinates = $this->geoCoder->fetchCoordinatesByAddress($completeAddress);
            if (is_null($coordinates)) {
                $coordinates = $this->geoCoder->fetchCoordinatesByAddress($legalNameCell->getValue());
            }
            if (is_null($coordinates)) {
                continue;
            }

            $affiliates[] = new Affiliate(
                (string)$row->getRowIndex(),
                (string)$legalNameCell->getValue(),
                (string)$streetAddressCell->getValue(),
                (string)$postalCodeCell->getValue(),
                (string)$addressLocalityCell->getValue(),
                (string)$addressCountryCell->getValue(),
                $emailCell->getValue() ? explode(PHP_EOL, $emailCell->getValue()) : null,
                (string)$telephoneCell->getValue(),
                $coordinates->getLatitude(),
                $coordinates->getLongitude()
            );
        }

        if ($locale != 'de') {
            usort($affiliates, function ($affiliateA, $affiliateB) {
                return strcmp($affiliateA->getAddressCountry(), $affiliateB->getAddressCountry());
            });
        }

        return new Affiliates($affiliates);
    }

    public function getDataSet(): Spreadsheet
    {
        $basePath = pathinfo($this->dataSetPath, PATHINFO_DIRNAME);
        $baseName = pathinfo($this->dataSetPath, PATHINFO_FILENAME);

        if (file_exists($basePath . '/' . $baseName . '.xlsx')) {
            $fileToLoad = $basePath . '/' . $baseName . '.xlsx';
        } elseif (file_exists($basePath . '/' . $baseName . '.xls')) {
            $fileToLoad = $basePath . '/' . $baseName . '.xls';
        } else {
            throw new \RuntimeException('No dataset file found for' . $baseName);
        }

        $reader = IOFactory::createReaderForFile(
            $fileToLoad
        );

        $reader->setReadDataOnly(true);

        return $reader->load($fileToLoad);
    }
}
