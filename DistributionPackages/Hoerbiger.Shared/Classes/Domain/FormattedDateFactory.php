<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Domain;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.PressArchive package.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\I18n\Formatter\DatetimeFormatter;
use Neos\Flow\I18n\Service as LocalizationService;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;

#[Flow\Scope('singleton')]
final class FormattedDateFactory extends AbstractComponentPresentationObjectFactory
{
    private LocalizationService $localizationService;

    private DatetimeFormatter $datetimeFormatter;

    public function __construct(
        LocalizationService $localizationService,
        DatetimeFormatter $datetimeFormatter
    ) {
        $this->localizationService = $localizationService;
        $this->datetimeFormatter = $datetimeFormatter;
    }

    public function createFullDate(\DateTimeInterface $dateTime): string
    {
        switch ($this->localizationService->getConfiguration()->getCurrentLocale()->getLanguage()) {
            case 'de':
                return $this->datetimeFormatter->formatDateTimeWithCustomPattern(
                    $dateTime,
                    'dd. MMMM y',
                    $this->localizationService->getConfiguration()->getCurrentLocale()
                );
            case 'en':
            default:
                return $dateTime->format('F dS, Y');
        }
    }
    public function createReducedYearDate(\DateTimeInterface $dateTime): string
    {

        switch ($this->localizationService->getConfiguration()->getCurrentLocale()->getLanguage()) {
            case 'de':
                $fullDateParts = $this->datetimeFormatter->formatDateTimeWithCustomPattern(
                    $dateTime,
                    'dd. MMMM yy',
                    $this->localizationService->getConfiguration()->getCurrentLocale()
                );
                break;
            case 'en':
            default:
                $fullDateParts = $dateTime->format('F dS, yy');
                break;
        }
        $fullDateParts = explode(' ', $fullDateParts);
        $fullDateParts[ 2 ] = "'" . $fullDateParts[ 2 ];

        $implode = implode(' ', $fullDateParts);
        return $implode;
    }
}
