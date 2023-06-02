<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Presentation\Block\Card;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use Hoerbiger\Shared\Infrastructure\CsvPOTranslator;
use Hoerbiger\Shared\Presentation\Block\Button\Button;
use Hoerbiger\Shared\Presentation\Block\Button\ButtonType;
use Hoerbiger\Shared\Presentation\Block\Button\ButtonVariant;
use Hoerbiger\Shared\Presentation\Block\Card\DownloadCard\DownloadCard;
use Hoerbiger\Shared\Presentation\Block\Card\CardInterface;
use Hoerbiger\Shared\Presentation\Block\Card\NavigationCard\NavigationCard;
use Hoerbiger\Shared\Presentation\Block\Card\SearchResultCard\SearchResultCard;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\ImageSourceFactory;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineColor;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineSize;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineType;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineVariant;
use Hoerbiger\Shared\Presentation\Block\Icon\Icon;
use Hoerbiger\Shared\Presentation\Block\Icon\IconSize;
use Hoerbiger\Shared\Presentation\Block\Link\Link;
use Hoerbiger\Shared\Presentation\Block\Link\LinkFactory;
use Hoerbiger\Shared\Presentation\Block\Link\LinkTarget;
use Hoerbiger\Shared\Presentation\Block\Link\LinkVariant;
use Hoerbiger\Shared\Presentation\Block\Text\Text;
use Hoerbiger\Shared\Presentation\Block\Text\TextVariant;
use Hoerbiger\Shared\Presentation\EnumProvider\Theme\Theme;
use Hoerbiger\Shared\Presentation\Layout\Grid\Grid;
use Hoerbiger\Shared\Presentation\Layout\Grid\GridVariant;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Media\Domain\Model\Document;
use Neos\Utility\Files;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Editable;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;
use Sitegeist\CsvPO\Domain\TranslationLabelSourceRepository;
use Neos\Flow\I18n\Service as LocalizationService;
use Neos\Flow\Annotations as Flow;

final class CardFactory extends AbstractComponentPresentationObjectFactory implements CardFactoryInterface
{
    private ImageSourceFactory $imageSourceFactory;

    private FigureFactory $figureFactory;

    private CsvPOTranslator $csvPOTranslator;

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

    public function __construct(
        ImageSourceFactory $imageSourceFactory,
        FigureFactory $figureFactory,
        CsvPOTranslator $csvPOTranslator
    ) {
        $this->imageSourceFactory = $imageSourceFactory;
        $this->figureFactory = $figureFactory;
        $this->csvPOTranslator = $csvPOTranslator;
    }

    public function forNavigationTile(TraversableNodeInterface $document, bool $inBackend, ?Theme $theme): NavigationCard
    {
        $link = (new LinkFactory())->forLinkToDocument($document, $theme);

        $imageSource = $this->imageSourceFactory->forPreviewMixin($document, $inBackend);
        $figure = $imageSource
            ? $link->asContainer()->withContent($this->figureFactory->forNavigationCard($imageSource))
            : null;

        $headline = null;
        $headlineProperty = $document->getProperty('preview__headline')
            ?: $document->getProperty('headline')
            ?: null;

        if ($headlineProperty) {
            $headline = new Headline(
                HeadlineSize::SIZE_VERY_SMALL_REGULAR,
                HeadlineColor::tryFromTheme($theme, false) ?: HeadlineColor::COLOR_NEUTRAL_CONTRAST,
                HeadlineType::TYPE_DIV,
                Value::fromString($headlineProperty)
            );
        }

        $text = null;
        $abstractProperty = $document->getProperty('preview__description')
            ?: $document->getProperty('abstract')
            ?: null;

        if ($abstractProperty) {
            $text = new Text(
                TextVariant::tryFromTheme($theme, false) ?: TextVariant::VARIANT_LIGHT_CONTRAST,
                Value::fromString($abstractProperty),
                false
            );
        }

        return new NavigationCard(
            $figure,
            $headline,
            null,
            $text,
            new Button(
                ButtonType::TYPE_LINK,
                ButtonVariant::VARIANT_PLAIN,
                new Grid(
                    GridVariant::VARIANT_ICONLEFT,
                    Collection::fromIterable([
                        new Icon("shared", "arrowRight", IconSize::SIZE_SIZE_1),
                        Value::fromString(
                            $this->translator->translateById('link.label', [], null, null, 'Block.Card.NavigationCard', 'Hoerbiger.HoerbigerCom') ?: ''
                        )
                    ])
                ),
                $this->uriService->getNodeUri($document),
                '_self',
                null
            )
        );
    }

    public function forDocument(Document $document, string $typeIdentifier, ?string $highlight): CardInterface
    {
        $headline = new Headline(
            HeadlineSize::SIZE_TINY,
            HeadlineColor::COLOR_BRAND,
            HeadlineType::TYPE_H2,
            Value::fromString($document->getLabel())
        );

        return new SearchResultCard(
            null,
            $headline,
            Value::fromString($this->getTypeLabel($typeIdentifier)),
            $highlight ? Value::fromString($highlight) : null,
            new Icon("shared", "downloadFile", IconSize::SIZE_SIZE_2)
        );
    }

    public function forThing(TraversableNodeInterface $thing, string $typeIdentifier, ?string $highlight): CardInterface
    {
        $supportingText = $highlight ?: $thing->getProperty('preview__description');
        $headline = new Headline(
            HeadlineSize::SIZE_TINY,
            HeadlineColor::COLOR_BRAND,
            HeadlineType::TYPE_H2,
            Value::fromString($thing->getLabel())
        );

        return new SearchResultCard(
            null,
            $headline,
            null,
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

    public function forDownloadNode(TraversableNodeInterface $downloadNode, ?Theme $theme): DownloadCard
    {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        $asset = $downloadNode->getProperty('asset');

        return new DownloadCard(
            ($asset instanceof Document) ? $this->getMediaForDocument($asset) : null,
            Editable::fromNodeProperty($downloadNode, 'title'),
            null,
            Editable::fromNodeProperty($downloadNode, 'description'),
            $asset instanceof Document
                ? Collection::fromIterable([
                    new Link(
                        LinkVariant::tryFromTheme($theme) ?: LinkVariant::VARIANT_LABEL,
                        $this->uriService->getAssetUri($asset),
                        LinkTarget::TARGET_SELF,
                        null,
                        Value::fromString($this->csvPOTranslator->getLabel(DownloadCard::class, 'download')),
                        null
                    ),
                    Value::fromString(
                        $this->csvPOTranslator->getLabel(DownloadCard::class, $asset->getFileExtension())
                            . ', ' . Files::bytesToSizeString($asset->getResource()->getFileSize())
                    )
                ], $identity)
                : null,
        );
    }

    private function getMediaForDocument(Document $asset): SlotInterface
    {
        switch ($asset->getFileExtension()) {
            case 'pdf':
                $imageSource = $this->imageSourceFactory->forDocument($asset);
                return $imageSource
                    ? $this->figureFactory->forDownloadCard(
                        $imageSource
                    )
                    : new Icon('shared', 'pdf');
            case 'ppt':
            case 'pptx':
                return new Icon('shared', 'powerpoint');
            default:
                return new Icon('shared', 'downloadFile');
        }
    }
}
