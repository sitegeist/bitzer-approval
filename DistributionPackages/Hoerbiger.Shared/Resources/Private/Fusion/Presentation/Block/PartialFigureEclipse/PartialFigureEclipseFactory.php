<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\PartialFigureEclipse;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureObjectFit;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureObjectPosition;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineType;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineVariant;
use Hoerbiger\Shared\Presentation\Block\Link\LinkFactory;
use Hoerbiger\Shared\Presentation\Block\Text\Text;
use Hoerbiger\Shared\Presentation\Block\Text\TextVariant;
use Hoerbiger\Shared\Presentation\Layout\Stack\Stack;
use Hoerbiger\Shared\Presentation\Layout\Stack\StackVariant;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Neos\Domain\Service\ContentContext;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Editable;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

final class PartialFigureEclipseFactory extends AbstractComponentPresentationObjectFactory
{
    public function forImageOverLappedByTextNode(TraversableNodeInterface $node, ContentContext $subgraph, bool $inBackend): PartialFigureEclipseInterface
    {
        $figure = (new FigureFactory())->forImageMixin($node, $inBackend);
        if (!$figure) {
            throw new \InvalidArgumentException('Image must be given for ImageOverLappedByText in frontend.');
        }

        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        return new PartialFigureEclipse(
            PartialFigureEclipseVariant::from(
                $node->getProperty('variant')
                    ?: PartialFigureEclipseVariant::VARIANT_IMAGELEFT
            ),
            $figure->withObjectFitAndPosition(
                FigureObjectFit::cover(),
                FigureObjectPosition::center()
            ),
            new Stack(
                StackVariant::xs(),
                Collection::fromIterable(array_filter([
                    ($inBackend || $node->getProperty('headline'))
                        ? new Headline(
                            HeadlineVariant::important(),
                            HeadlineType::h2(),
                            Editable::fromNodeProperty($node, 'headline')
                        )
                        : null,
                    new Text(
                        TextVariant::lightContrast(),
                        Editable::fromNodeProperty($node, 'text'),
                        false
                    ),
                    (new LinkFactory())->forLinkMixin($node, $subgraph)
                ]), $identity)
            )
        );
    }
}
