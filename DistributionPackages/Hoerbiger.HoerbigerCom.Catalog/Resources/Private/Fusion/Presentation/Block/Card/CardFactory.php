<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Catalog\Presentation\Block\Card;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.Catalog package.
 */

use Hoerbiger\HoerbigerCom\Presentation\Block\Card\CardFactoryInterface;
use Hoerbiger\Shared\Presentation\Block\Card\CardInterface;
use Hoerbiger\Shared\Presentation\Block\Card\SearchResultCard\SearchResultCard;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;
use Sitegeist\CsvPO\Domain\TranslationLabelSourceRepository;
use Neos\Flow\I18n\Service as LocalizationService;
use Neos\Flow\Annotations as Flow;

final class CardFactory extends AbstractComponentPresentationObjectFactory implements CardFactoryInterface
{
    /**
     * @var TranslationLabelSourceRepository
     * @Flow\Inject
     */
    protected $translationLabelSourceRepository;

    /**
     * @Flow\Inject
     * @var LocalizationService
     */
    protected $localizationService;

    public function forThing(TraversableNodeInterface $thing, string $typeIdentifier): CardInterface
    {
        $labelSource = $this->translationLabelSourceRepository->findOneByIdentifier(
            'resource://Hoerbiger.HoerbigerCom/Private/Fusion/Presentation/Application/SearchForm/SearchForm.translation.csv'
        );
        $label = $labelSource
            ? $labelSource->findTranslationLabelByIdentifier('TypeIdentifier.' . $typeIdentifier)
            : null;
        $translatedLabel = $label
            ? $label->findTranslationForLocaleChain($this->localizationService->getLocaleChain(
                $this->localizationService->getConfiguration()->getCurrentLocale()
            ))->getTranslation()
            : null;

        return new SearchResultCard(
            null,
            Value::fromString($thing->getLabel()),
            Value::fromString($translatedLabel ?: $typeIdentifier),
            $thing->getProperty('preview__description')
                ? Value::fromString($thing->getProperty('preview__description'))
                : null,
            null
        );
    }
}
