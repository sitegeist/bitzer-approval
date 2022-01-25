<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\PartialFigureEclipse;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\ImageSourceFactory;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineType;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineVariant;
use Hoerbiger\Shared\Presentation\Block\Link\LinkFactory;
use Hoerbiger\Shared\Presentation\Block\Text\Text;
use Hoerbiger\Shared\Presentation\Block\Text\TextVariant;
use Hoerbiger\Shared\Presentation\EnumProvider\Theme\Theme;
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
    private ImageSourceFactory $imageSourceFactory;

    private FigureFactory $figureFactory;

    public function __construct(
        ImageSourceFactory $imageSourceFactory,
        FigureFactory $figureFactory
    ) {
        $this->imageSourceFactory = $imageSourceFactory;
        $this->figureFactory = $figureFactory;
    }

    public function forImageOverLappedByTextNode(TraversableNodeInterface $node, ContentContext $subgraph, bool $inBackend, ?Theme $theme): PartialFigureEclipseInterface
    {
        $imageSource = $this->imageSourceFactory->forImageMixin($node, $inBackend);
        if (!$imageSource) {
            throw new \InvalidArgumentException('Image must be given for ImageOverLappedByText in frontend.');
        }
        $figure = $this->figureFactory->forPartialEclipse($imageSource);

        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        return new PartialFigureEclipse(
            PartialFigureEclipseVariant::from(
                $node->getProperty('variant')
                    ?: PartialFigureEclipseVariant::VARIANT_IMAGELEFT
            ),
            $figure,
            new Stack(
                StackVariant::xs(),
                Collection::fromIterable(array_filter([
                    ($inBackend || $node->getProperty('headline'))
                        ? new Headline(
                            // theme
                            HeadlineVariant::mediumBrand(),
                            HeadlineType::div(),
                            Editable::fromNodeProperty($node, 'headline')
                        )
                        : null,
                    new Text(
                        TextVariant::tryFromTheme($theme, false) ?: TextVariant::lightContrast(),
                        Editable::fromNodeProperty($node, 'text'),
                        false
                    ),
                    (new LinkFactory())->forLinkMixin($node, $subgraph, $theme)
                ]), $identity)
            )
        );
    }
}
