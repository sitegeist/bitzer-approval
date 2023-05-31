<?php

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\SuccessStories\Presentation\Block\Card;

use Hoerbiger\HoerbigerCom\Presentation\Block\Card\CardFactoryInterface;
use Hoerbiger\Shared\Presentation\Block\Card\CardInterface;
use Hoerbiger\Shared\Presentation\Block\Card\SearchResultCard\SearchResultCard;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineColor;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineSize;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineType;
use Neos\ContentRepository\Domain\NodeType\NodeTypeName;
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

    public function forThing(TraversableNodeInterface $thing, string $typeIdentifier, ?string $highlight): CardInterface
    {
        $supportingText = $highlight
            ?: $thing->getProperty('preview__description')
            ?: $thing->getProperty('abstract');

        $headline = new Headline(
            HeadlineSize::SIZE_TINY,
            HeadlineColor::COLOR_BRAND,
            HeadlineType::TYPE_H2,
            Value::fromString($thing->getLabel())
        );

        return new SearchResultCard(
            null,
            $headline,
            Value::fromString($this->getTypeLabel($typeIdentifier)),
            $supportingText ? Value::fromString($supportingText) : null,
            null
        );
    }

    private function getTypeLabel(string $typeIdentifier): string
    {
        $labelSource = $this->translationLabelSourceRepository->findOneByIdentifier(
            'resource://Hoerbiger.HoerbigerCom/Private/Fusion/Presentation/Application/SearchForm/SearchForm.translation.csv'
        );
        $label = $labelSource
            ? $labelSource->findTranslationLabelByIdentifier('type.' . $typeIdentifier)
            : null;

        return $label
            ? $label->findTranslationForLocaleChain($this->localizationService->getLocaleChain(
                $this->localizationService->getConfiguration()->getCurrentLocale()
            ))->getTranslation() ?: $typeIdentifier
            : $typeIdentifier;
    }
}
