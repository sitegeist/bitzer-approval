<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Stage;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Domain\FormattedDateFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\ImageSourceFactory;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineType;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineVariant;
use Hoerbiger\Shared\Presentation\Layout\Stage\DuoStage\DuoStage;
use Hoerbiger\Shared\Presentation\Layout\Stage\FullMediaStage\FullMediaStage;
use Hoerbiger\Shared\Presentation\Block\Text\Text;
use Hoerbiger\Shared\Presentation\Block\Text\TextVariant;
use Hoerbiger\Shared\Presentation\Layout\Row\Row;
use Hoerbiger\Shared\Presentation\Layout\Row\RowVariant;
use Hoerbiger\Shared\Presentation\Layout\Stage\StackStage\StackStage;
use Neos\ContentRepository\Domain\Model\Node;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Editable;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;

final class StageFactory extends AbstractComponentPresentationObjectFactory
{
    private ImageSourceFactory $imageSourceFactory;

    private FigureFactory $figureFactory;

    private FormattedDateFactory $formattedDateFactory;

    public function __construct(
        ImageSourceFactory $imageSourceFactory,
        FigureFactory $figureFactory,
        FormattedDateFactory $formattedDateFactory
    ) {
        $this->imageSourceFactory = $imageSourceFactory;
        $this->figureFactory = $figureFactory;
        $this->formattedDateFactory = $formattedDateFactory;
    }

    public function forFullMediaIntroduction(TraversableNodeInterface $node, bool $inBackend): StageInterface
    {
        $imageSource = $this->imageSourceFactory->forImageMixin($node, $inBackend, true);
        if (!$imageSource) {
            throw new \InvalidArgumentException('Introduction requires image for property media.');
        }

        return new FullMediaStage(
            StageVariant::from($node->getProperty('stage__variant') ?: StageVariant::VARIANT_FULLMEDIATEXTLEFT),
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
            null,
            $this->figureFactory->forFullMediaStage($imageSource)
        );
    }

    public function forDuoIntroduction(TraversableNodeInterface $node, bool $inBackend): StageInterface
    {
        $imageSource = $this->imageSourceFactory->forImageMixin($node, $inBackend, true);
        if (!$imageSource) {
            throw new \InvalidArgumentException('Introduction requires image for property media.');
        }

        return new DuoStage(
            StageVariant::duo(),
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
            $this->figureFactory->forDuoStage($imageSource),
            null
        );
    }

    public function forPressReleaseIntroduction(TraversableNodeInterface $pressRelease, bool $inBackend): StageInterface
    {
        $imageSource = $this->imageSourceFactory->forImageMixin($pressRelease, $inBackend, true);

        return new StackStage(
            StageVariant::stack(),
            new Headline(
                HeadlineVariant::brand(),
                HeadlineType::h1(),
                Editable::fromNodeProperty($pressRelease, 'headline', false)
            ),
            new Row(
                RowVariant::ch(),
                Collection::fromIterable(array_filter([
                    $this->getPressReleasePublishDate($pressRelease),
                    Value::fromString('ꞏ'),
                    Editable::fromNodeProperty($pressRelease, 'alternativeHeadline')
                ]), function(SlotInterface $slot) {
                    return $slot;
                })
            ),
            new Text(
                TextVariant::lightContrast(),
                Editable::fromNodeProperty($pressRelease, 'abstract'),
                false
            ),
            $imageSource
                ? $this->figureFactory->forStackStage($imageSource)
                : null,
            null
        );
    }

    private function getPressReleasePublishDate(TraversableNodeInterface $pressRelease): SlotInterface
    {
        /** @var Node $pressRelease */
        $datePublished = $pressRelease->getProperty('datePublished') ?: $pressRelease->getCreationDateTime();

        return Value::fromString($this->formattedDateFactory->createFullDate($datePublished));
    }
}
