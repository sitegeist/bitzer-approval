<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Domain\Translation;

use Neos\Utility\Files;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as ExcelWriter;
use Neos\Flow\Annotations as Flow;
use Psr\Http\Message\UriInterface;

class DocumentExportFile implements ExportFileInterface
{
    /** @phpstan-ignore-next-line known constant */
    public const TRANSLATION_DIRECTORY = FLOW_PATH_DATA . 'Temporary/Translation/';
    public const FILE_PREFIX = 'Document_Translation_Export_';
    public const FILE_EXTENSION = '.xlsx';
    private const COLUMN_A = 'Node Identifier';
    private const COLUMN_B = 'Property Name';
    private const COLUMN_C = 'Source Content';
    private const COLUMN_D = 'Target Content';
    private int $currentRowIndex;

    private string $fileName;

    public function __construct(
        public readonly Spreadsheet $spreadsheet,
        public readonly string $targetLanguage,
        public readonly UriInterface $documentUri
    ) {
        $this->fileName =  self::FILE_PREFIX
            . '_' . $targetLanguage . '_'
            . (new \DateTimeImmutable('now'))->format('YmdHi')
            . self::FILE_EXTENSION;
        $this->setHeaders();
    }

    public function getFileName(): ?string
    {
        if (file_exists(self::TRANSLATION_DIRECTORY . $this->fileName)) {
            return $this->fileName;
        }

        return null;
    }

    public function writeNewRow(
        ExportRecord $record,
    ): void {
        $row = $this->spreadsheet->getActiveSheet()->getRowIterator(1)->current();

        $row->getWorksheet()->getCell('A' . $this->currentRowIndex)->setValue(
            (string)$record->nodeIdentifier
        );

        $row->getWorksheet()->getCell('B' . $this->currentRowIndex)->setValue(
            $record->propertyName
        );

        $row->getWorksheet()->getCell('C' . $this->currentRowIndex)->setValue(
            $record->contentInSourceLanguage
        );

        $row->getWorksheet()->getCell('D' . $this->currentRowIndex)->setValue(
            $record->contentInTargetLanguage
        );

        $this->currentRowIndex++;
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

    public function remove(): void
    {
        $filePath = self::TRANSLATION_DIRECTORY . $this->fileName;
        Files::unlink($filePath);
    }

    private function setHeaders(): void
    {
        $metaHeaderRow = $this->spreadsheet->getActiveSheet()->getRowIterator(1)->current();
        $this->currentRowIndex = $metaHeaderRow->getRowIndex();

        $metaHeaderRow->getWorksheet()->getCell('A' . $this->currentRowIndex)->setValue(
            'Language:'
        );
        $metaHeaderRow->getWorksheet()->getCell('B' . $this->currentRowIndex)->setValue(
            $this->targetLanguage
        );
        $this->currentRowIndex++;
        $this->spreadsheet->getActiveSheet()->insertNewRowBefore($this->currentRowIndex, 1);

        $metaHeaderRow->getWorksheet()->getCell('A' . $this->currentRowIndex)->setValue(
            'Document Uri:'
        );
        $metaHeaderRow->getWorksheet()->getCell('B' . $this->currentRowIndex)->setValue(
            (string)$this->documentUri
        );
        $this->currentRowIndex++;
        $this->spreadsheet->getActiveSheet()->insertNewRowBefore($this->currentRowIndex, 1);
        $this->currentRowIndex++;
        $this->spreadsheet->getActiveSheet()->insertNewRowBefore($this->currentRowIndex, 1);
        $headerRow = $this->spreadsheet->getActiveSheet()->getRowIterator($this->currentRowIndex)->current();

        $headerRow->getWorksheet()->getCell('A' . $this->currentRowIndex)->setValue(
            self::COLUMN_A
        );
        $headerRow->getWorksheet()->getCell('B' . $this->currentRowIndex)->setValue(
            self::COLUMN_B
        );
        $headerRow->getWorksheet()->getCell('C' . $this->currentRowIndex)->setValue(
            self::COLUMN_C
        );
        $headerRow->getWorksheet()->getCell('D' . $this->currentRowIndex)->setValue(
            self::COLUMN_D
        );
        $this->currentRowIndex++;
        $this->spreadsheet->getActiveSheet()->insertNewRowBefore($this->currentRowIndex, 1);

        $row = $this->spreadsheet->getActiveSheet()->getRowIterator($this->currentRowIndex)->current();

        $this->currentRowIndex = $row->getRowIndex();
    }
}
