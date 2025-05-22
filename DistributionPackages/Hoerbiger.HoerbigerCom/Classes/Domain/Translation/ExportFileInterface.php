<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Domain\Translation;

interface ExportFileInterface
{
    public function writeNewRow(ExportRecord $record): void;
    public function persist(string $targetLanguage): string;
    public function remove(): void;
    public function getFileName(): ?string;
}
