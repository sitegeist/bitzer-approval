<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Presentation\Block\Card;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use Hoerbiger\Shared\Presentation\Block\Card\ArticleCard\ArticleCard;
use Hoerbiger\Shared\Presentation\Block\Card\CardInterface;
use Hoerbiger\Shared\Presentation\Block\Card\NavigationCard\NavigationCard;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\ImageSourceFactory;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineType;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineVariant;
use Hoerbiger\Shared\Presentation\Block\Link\LinkFactory;
use Hoerbiger\Shared\Presentation\Block\Text\Text;
use Hoerbiger\Shared\Presentation\Block\Text\TextVariant;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Media\Domain\Model\Document;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;

final class CardFactory extends AbstractComponentPresentationObjectFactory implements CardFactoryInterface
{
    private ImageSourceFactory $imageSourceFactory;

    private FigureFactory $figureFactory;

    public function __construct(
        ImageSourceFactory $imageSourceFactory,
        FigureFactory $figureFactory
    ) {
        $this->imageSourceFactory = $imageSourceFactory;
        $this->figureFactory = $figureFactory;
    }

    public function forNavigationTile(TraversableNodeInterface $document, bool $inBackend): NavigationCard
    {
        $link = (new LinkFactory())->forLinkToDocument($document);

        $imageSource = $this->imageSourceFactory->forImageProperty($document, $inBackend, 'preview__image');
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

    public function forDocument(Document $document): CardInterface
    {
        return new ArticleCard(
            null,
            Value::fromString($document->getLabel()),
            null,
            null,
            null
        );
    }

    public function forThing(TraversableNodeInterface $thing): CardInterface
    {
        return new ArticleCard(
            null,
            Value::fromString($thing->getLabel()),
            null,
            null,
            null
        );
    }
}
