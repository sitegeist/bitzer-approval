<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\SuccessStories\Presentation\Layout\Stage;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.SuccessStories package.
 */

use Hoerbiger\Shared\Domain\FormattedDateFactory;
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

    public function forSuccessStoryIntroduction(TraversableNodeInterface $successStory, bool $inBackend): StageInterface
    {
        $imageSource = $this->imageSourceFactory->forImageMixin($successStory, $inBackend, true);

        /** @var Node $successStory */
        $datePublished = $successStory->getProperty('datePublished') ?: $successStory->getCreationDateTime();
        /** @var TraversableNodeInterface $successStory */

        return new StackStage(
            StageVariant::stack(),
            new Row(
                RowVariant::alternateMetaline(),
                Collection::fromIterable(array_filter([
                    //Value::fromString($this->formattedDateFactory->createFullDate($datePublished)),
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
                HeadlineSize::large(),
                HeadlineColor::brand(),
                HeadlineType::h1(),
                Editable::fromNodeProperty($successStory, 'headline', false)
            ),
            $inBackend || $successStory->getProperty('alternativeHeadline')
                ? Editable::fromNodeProperty($successStory, 'alternativeHeadline')
                : null,
            new Text(
                TextVariant::lightContrast(),
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
