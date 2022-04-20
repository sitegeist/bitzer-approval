<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Presentation\Block\Card;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use Hoerbiger\Shared\Infrastructure\CsvPOTranslator;
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
                HeadlineSize::small(),
                HeadlineColor::tryFromTheme($theme, false) ?: HeadlineColor::brand(),
                HeadlineType::div(),
                Value::fromString($headlineProperty)
            );
        }

        $text = null;
        $abstractProperty = $document->getProperty('preview__description')
            ?: $document->getProperty('abstract')
            ?: null;

        if ($abstractProperty) {
            $text = new Text(
                TextVariant::tryFromTheme($theme, false) ?: TextVariant::lightContrast(),
                Value::fromString($abstractProperty),
                false
            );
        }

        return new NavigationCard(
            $figure,
            $headline,
            null,
            $text,
            $link->withContent(
                Value::fromString(
                    $this->translator->translateById('link.label', [], null, null, 'Block.Card.NavigationCard', 'Hoerbiger.HoerbigerCom') ?: ''
                )
            )
        );
    }

    public function forDocument(Document $document, string $typeIdentifier, ?string $highlight): CardInterface
    {
        return new SearchResultCard(
            null,
            Value::fromString($document->getLabel()),
            Value::fromString($this->getTypeLabel($typeIdentifier)),
            $highlight ? Value::fromString($highlight) : null,
            new Icon("shared", "downloadFile", IconSize::size_2())
        );
    }

    public function forThing(TraversableNodeInterface $thing, string $typeIdentifier, ?string $highlight): CardInterface
    {
        $supportingText = $highlight ?: $thing->getProperty('preview__description');

        return new SearchResultCard(
            null,
            Value::fromString($thing->getLabel()),
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
                        LinkVariant::tryFromTheme($theme) ?: LinkVariant::label(),
                        $this->uriService->getAssetUri($asset),
                        LinkTarget::blank(),
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
