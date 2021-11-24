<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Presentation\Block\Card;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Infrastructure\CsvPOTranslator;
use Hoerbiger\Shared\Presentation\Block\Card\DownloadCard\DownloadCard;
use Hoerbiger\Shared\Presentation\Block\Card\NavigationCard\NavigationCard;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\ImageSourceFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\PresetIdentifier;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineType;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineVariant;
use Hoerbiger\Shared\Presentation\Block\Icon\Icon;
use Hoerbiger\Shared\Presentation\Block\Link\Link;
use Hoerbiger\Shared\Presentation\Block\Link\LinkFactory;
use Hoerbiger\Shared\Presentation\Block\Link\LinkTarget;
use Hoerbiger\Shared\Presentation\Block\Link\LinkVariant;
use Hoerbiger\Shared\Presentation\Block\Text\Text;
use Hoerbiger\Shared\Presentation\Block\Text\TextVariant;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Media\Domain\Model\Document;
use Neos\Utility\Files;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Editable;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;

final class CardFactory extends AbstractComponentPresentationObjectFactory
{
    private ImageSourceFactory $imageSourceFactory;
    private FigureFactory $figureFactory;
    private CsvPOTranslator $csvPOTranslator;

    public function __construct(
        ImageSourceFactory $imageSourceFactory,
        FigureFactory $figureFactory,
        CsvPOTranslator $csvPOTranslator
    ) {
        $this->imageSourceFactory = $imageSourceFactory;
        $this->figureFactory = $figureFactory;
        $this->csvPOTranslator = $csvPOTranslator;
    }

    public function forNavigationTile(TraversableNodeInterface $document, bool $inBackend): NavigationCard
    {
        $link = (new LinkFactory())->forLinkToDocument($document);

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
                HeadlineVariant::regular(),
                HeadlineType::h2(),
                Value::fromString($headlineProperty)
            );
        }

        $text = null;
        $abstractProperty = $document->getProperty('preview__description')
            ?: $document->getProperty('abstract')
            ?: null;

        if ($abstractProperty) {
            $text = new Text(
                TextVariant::lightContrast(),
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

    public function forDownloadNode(TraversableNodeInterface $downloadNode): DownloadCard
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
                    LinkVariant::label(),
                    $this->uriService->getAssetUri($asset),
                    LinkTarget::blank(),
                    null,
                    Value::fromString($this->csvPOTranslator->getLabel(DownloadCard::class, 'download')),
                ),
                Value::fromString(
                    $this->csvPOTranslator->getLabel(DownloadCard::class, $asset->getFileExtension())
                    . ', ' . Files::bytesToSizeString($asset->getResource()->getFileSize())
                )
            ], $identity)
                : null,
        );
    }

    private function getMediaForDocument(Document $asset) : SlotInterface
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
