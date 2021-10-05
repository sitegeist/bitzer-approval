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
use Hoerbiger\Shared\Presentation\Block\IntroSection\DuoIntroSection\DuoIntroSection;
use Hoerbiger\Shared\Presentation\Block\IntroSection\FullMediaIntroSection\FullMediaIntroSection;
use Hoerbiger\Shared\Presentation\Block\Text\Text;
use Hoerbiger\Shared\Presentation\Block\Text\TextVariant;
use InvalidArgumentException;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Editable;

final class IntroSectionFactory extends AbstractComponentPresentationObjectFactory
{
    public function forFullMediaIntroduction(TraversableNodeInterface $node, bool $inBackend): IntroSectionInterface
    {
        /** @@var Figure|null $media */
        $media = (new FigureFactory())->forImageMixin($node, $inBackend, true);

        if (!$media) {
            throw new InvalidArgumentException('Introduction requires Block.Figure for property media.');
        }

        return new FullMediaIntroSection(
            IntroSectionVariant::from($node->getProperty('introsection__variant') ?: IntroSectionVariant::VARIANT_FULLMEDIATEXTLEFT),
            new Headline(
                HeadlineVariant::regular(),
                HeadlineType::h1(),
                Editable::fromNodeProperty($node, 'headline')
            ),
            null,
            new Text(
                TextVariant::transparentContrast(),
                Editable::fromNodeProperty($node, 'abstract'),
                false
            ),
            $media->withObjectFitAndPosition(FigureObjectFit::cover(), FigureObjectPosition::center())
        );
    }

    public function forDuoIntroduction(TraversableNodeInterface $node, bool $inBackend): IntroSectionInterface
    {
        /** @@var Figure|null $media */
        $media = (new FigureFactory())->forImageMixin($node, $inBackend, true);

        if (!$media) {
            throw new InvalidArgumentException('Introduction requires Block.Figure for property media.');
        }

        return new DuoIntroSection(
            IntroSectionVariant::duo(),
            new Headline(
                HeadlineVariant::decorative(),
                HeadlineType::h1(),
                Editable::fromNodeProperty($node, 'headline', false)
            ),
            null,
            new Text(
                TextVariant::lightContrast(),
                Editable::fromNodeProperty($node, 'abstract'),
                false
            ),
            $media->withObjectFitAndPosition(FigureObjectFit::cover(), FigureObjectPosition::center())
        );
    }
}
