<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Presentation\Block\Card;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\Block\Card\NavigationCard\NavigationCard;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineType;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineVariant;
use Hoerbiger\Shared\Presentation\Block\Link\LinkFactory;
use Hoerbiger\Shared\Presentation\Block\Text\Text;
use Hoerbiger\Shared\Presentation\Block\Text\TextVariant;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Neos\Domain\Service\ContentContext;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;

final class CardFactory extends AbstractComponentPresentationObjectFactory
{
    public function forNavigationTile(TraversableNodeInterface $document, bool $inBackend, ContentContext $subgraph): NavigationCard
    {
        $link = (new LinkFactory())->forLinkToDocument($document, $subgraph);

        /** @var SlotInterface|null $figure */
        $figure = (new FigureFactory())->forPreviewImageMixin($document, $inBackend);

        if ($figure) {
            $figure = $link->asContainer()->withContent($figure);
        }

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
                Value::fromString($abstractProperty)
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

}
