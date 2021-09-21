<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\PartialFigureEclipse;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

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
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Editable;

final class PartialFigureEclipseFactory extends AbstractComponentPresentationObjectFactory
{
    public function forImageOverLappedByTextNode(TraversableNodeInterface $node, ContentContext $subgraph, bool $inBackend): PartialFigureEclipseInterface
    {
        $figure = (new FigureFactory())->forImageMixin($node, $inBackend);
        if (!$figure) {
            throw new \InvalidArgumentException('Image must be given for ImageOverLappedByText in frontend.');
        }

        return new PartialFigureEclipse(
            PartialFigureEclipseVariant::from($node->getProperty('variant')),
            $figure,
            new Headline(
                HeadlineVariant::regular(),
                HeadlineType::h2(),
                Editable::fromNodeProperty($node, 'headline')
            ),
            new Text(
                TextVariant::lightContrast(),
                Editable::fromNodeProperty($node, 'text')
            ),
            (new LinkFactory())->forLinkMixin($node, $subgraph)
        );
    }
}
