<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\SuccessStories\Presentation\Layout\Stage;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.SuccessStories package.
 */

use Hoerbiger\Shared\Integration\CacheSegment\CacheSegment;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\ImageSourceFactory;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineColor;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineSize;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineType;
use Hoerbiger\Shared\Presentation\Block\Text\Text;
use Hoerbiger\Shared\Presentation\Block\Text\TextVariant;
use Hoerbiger\Shared\Presentation\Layout\Row\Row;
use Hoerbiger\Shared\Presentation\Layout\Row\RowVariant;
use Hoerbiger\Shared\Presentation\Layout\Stage\StackStage\StackStage;
use Hoerbiger\Shared\Presentation\Layout\Stage\StageVariant;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Editable;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;

final class StageFactory extends AbstractComponentPresentationObjectFactory
{
    public function __construct(
        private readonly ImageSourceFactory $imageSourceFactory,
        private readonly FigureFactory $figureFactory,
    ) {
    }

    public function forSuccessStoryIntroduction(TraversableNodeInterface $successStory, bool $inBackend): StackStage
    {
        $imageSource = $this->imageSourceFactory->forImageMixin($successStory, $inBackend, true);

        return new StackStage(
            StageVariant::VARIANT_STACK,
            new Row(
                RowVariant::VARIANT_ALTERNATEMETALINE,
                Collection::fromIterable(array_filter([
                    new CacheSegment(
                        Value::fromString(
                            $successStory->getProperty('applicationWorld')
                                ? $successStory->getProperty('applicationWorld')->getLabel()
                                : ''),
                        'Hoerbiger.HoerbigerCom.ApplicationWorlds:CacheSegment.ApplicationWorld'
                    ),

                ]), function(SlotInterface $slot) {
                    return $slot;
                })
            ),
            new Headline(
                HeadlineSize::SIZE_SUPER_LARGE,
                HeadlineColor::COLOR_BRAND,
                HeadlineType::TYPE_H1,
                Editable::fromNodeProperty($successStory, 'headline', false)
            ),
            $inBackend || $successStory->getProperty('alternativeHeadline')
                ? Editable::fromNodeProperty($successStory, 'alternativeHeadline')
                : null,
            new Text(
                TextVariant::VARIANT_LIGHT_CONTRAST,
                Editable::fromNodeProperty($successStory, 'abstract'),
                false
            ),
            $imageSource
                ? $this->figureFactory->forStackStage($imageSource)
                : null,
            null
        );
    }
}
