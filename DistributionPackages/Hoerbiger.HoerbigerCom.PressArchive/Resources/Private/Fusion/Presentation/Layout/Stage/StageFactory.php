<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\PressArchive\Presentation\Layout\Stage;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.PressArchive package.
 */

use Hoerbiger\Shared\Domain\FormattedDateFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\ImageSourceFactory;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineType;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineVariant;
use Hoerbiger\Shared\Presentation\Block\Text\Text;
use Hoerbiger\Shared\Presentation\Block\Text\TextVariant;
use Hoerbiger\Shared\Presentation\Layout\Row\Row;
use Hoerbiger\Shared\Presentation\Layout\Row\RowVariant;
use Hoerbiger\Shared\Presentation\Layout\Stage\StackStage\StackStage;
use Hoerbiger\Shared\Presentation\Layout\Stage\StageInterface;
use Hoerbiger\Shared\Presentation\Layout\Stage\StageVariant;
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

    public function forPressReleaseIntroduction(TraversableNodeInterface $pressRelease, bool $inBackend): StageInterface
    {
        $imageSource = $this->imageSourceFactory->forImageMixin($pressRelease, $inBackend, true);

        /** @var Node $pressRelease */
        $datePublished = $pressRelease->getProperty('datePublished') ?: $pressRelease->getCreationDateTime();
        /** @var TraversableNodeInterface $pressRelease */

        return new StackStage(
            StageVariant::stack(),
            new Row(
                RowVariant::alternateMetaline(),
                Collection::fromIterable(array_filter([
                    Value::fromString($this->formattedDateFactory->createFullDate($datePublished))
                ]), function(SlotInterface $slot) {
                    return $slot;
                })
            ),
            new Headline(
                HeadlineVariant::brand(),
                HeadlineType::h1(),
                Editable::fromNodeProperty($pressRelease, 'headline', false)
            ),
            $inBackend || $pressRelease->getProperty('alternativeHeadline')
                ? Editable::fromNodeProperty($pressRelease, 'alternativeHeadline')
                : null,
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
}
