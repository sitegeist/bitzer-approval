<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Domain;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.PressArchive package.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\I18n\Formatter\DatetimeFormatter;
use Neos\Flow\I18n\Service as LocalizationService;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;

/**
 * @Flow\Scope("singleton")
 */
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
}
