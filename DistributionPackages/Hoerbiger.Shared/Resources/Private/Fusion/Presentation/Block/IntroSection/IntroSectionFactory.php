<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\IntroSection;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureObjectFit;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureObjectPosition;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineType;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineVariant;
use Hoerbiger\Shared\Presentation\Block\Text\Text;
use Hoerbiger\Shared\Presentation\Block\Text\TextVariant;
use InvalidArgumentException;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Editable;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;

final class IntroSectionFactory extends AbstractComponentPresentationObjectFactory
{
    public function forIntroductionMixin(TraversableNodeInterface $node, bool $inBackend): IntroSectionInterface
    {
        /** @@var Figure|null $media */
        $media = (new FigureFactory())->forImageMixin($node, $inBackend, true);

        if (!$media) {
            throw new InvalidArgumentException('Introduction requires Block.Figure for property media.');
        }

        return new IntroSection(
            IntroSectionVariant::fromNodeTypeName($node->getNodeTypeName()),
            new Headline(
                HeadlineVariant::regular(),
                HeadlineType::h1(),
                Editable::fromNodeProperty($node, 'headline')
            ),
            null,
            new Text(
                TextVariant::transparentContrast(),
                Editable::fromNodeProperty($node, 'abstract')
            ),
            $media->withObjectFitAndPosition(FigureObjectFit::cover(), FigureObjectPosition::center())
        );
    }
}
