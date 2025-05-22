<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Domain\Translation;

use Neos\Utility\Files;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as ExcelWriter;
use Neos\Flow\Annotations as Flow;

class SiteExportFile implements ExportFileInterface
{
    /** @phpstan-ignore-next-line known constant */
    public const TRANSLATION_DIRECTORY = FLOW_PATH_DATA . 'Temporary/Translation/';
    public const FILE_PREFIX = 'Site_Translation_Export_';
    public const FILE_EXTENSION = '.xlsx';

    private const COLUMN_A = 'Document Uri';
    private const COLUMN_B = 'Node Identifier';
    private const COLUMN_C = 'Property Name';
    private const COLUMN_D = 'Source Content';
    private const COLUMN_E = 'Target Content';
    private int $currentRowIndex;

    private string $fileName;

    public function __construct(
        public readonly Spreadsheet $spreadsheet,
        public readonly string $targetLanguage
    ) {
        $this->fileName = self::FILE_PREFIX
            . '_' . $targetLanguage . '_'
            . (new \DateTimeImmutable('now'))->format('YmdHi')
            . self::FILE_EXTENSION;
        $this->setHeaders();
    }

    public function writeNewRow(
        ExportRecord $record,
    ): void {
        $row = $this->spreadsheet->getActiveSheet()->getRowIterator(1)->current();

        $row->getWorksheet()->getCell('A' . $this->currentRowIndex)->setValue(
            (string)$record->uri ?: ''
        );

        $row->getWorksheet()->getCell('B' . $this->currentRowIndex)->setValue(
            (string)$record->nodeIdentifier
        );

        $row->getWorksheet()->getCell('C' . $this->currentRowIndex)->setValue(
            $record->propertyName
        );

        $row->getWorksheet()->getCell('D' . $this->currentRowIndex)->setValue(
            $record->contentInSourceLanguage
        );

        $row->getWorksheet()->getCell('E' . $this->currentRowIndex)->setValue(
            $record->contentInTargetLanguage
        );

        $this->currentRowIndex ++;
    }

    public function persist(string $targetLanguage): string
    {
        $excelFilePath = self::TRANSLATION_DIRECTORY . $this->fileName;

        if (file_exists($excelFilePath)) {
            Files::unlink($excelFilePath);
        }

        if (!is_dir(self::TRANSLATION_DIRECTORY)) {
            Files::createDirectoryRecursively(self::TRANSLATION_DIRECTORY);
        }
        $writer = new ExcelWriter($this->spreadsheet);
        $writer->save($excelFilePath);
        return $excelFilePath;
    }

    public function getFileName(): ?string
    {
        if (file_exists(self::TRANSLATION_DIRECTORY . $this->fileName)) {
            return $this->fileName;
        }
        return null;
    }

    public function remove(): void
    {
        $filePath = self::TRANSLATION_DIRECTORY . $this->fileName;
        Files::unlink($filePath);
    }

    private function setHeaders(): void
    {
        $metaHeaderRow = $this->spreadsheet->getActiveSheet()->getRowIterator(1)->current();
        $currentRowIndex = $metaHeaderRow->getRowIndex();
        $metaHeaderRow->getWorksheet()->getCell('A' . $currentRowIndex)->setValue(
            self::COLUMN_A
        );
        $metaHeaderRow->getWorksheet()->getCell('B' . $currentRowIndex)->setValue(
            self::COLUMN_B
        );
        $metaHeaderRow->getWorksheet()->getCell('C' . $currentRowIndex)->setValue(
            self::COLUMN_C
        );
        $metaHeaderRow->getWorksheet()->getCell('D' . $currentRowIndex)->setValue(
            self::COLUMN_D
        );
        $metaHeaderRow->getWorksheet()->getCell('E' . $currentRowIndex)->setValue(
            self::COLUMN_E
        );

        $this->spreadsheet->getActiveSheet()->insertNewRowBefore(2, 1);

        $row = $this->spreadsheet->getActiveSheet()->getRowIterator(2)->current();

        $this->currentRowIndex = $row->getRowIndex();
    }
}
