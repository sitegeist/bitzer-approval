<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\PressArchive\Presentation\Layout\Stage;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.PressArchive package.
 */

use Hoerbiger\HoerbigerCom\PressArchive\Presentation\EnumProvider\Press\PressVariant;
use Hoerbiger\Shared\Domain\FormattedDateFactory;
use Hoerbiger\Shared\Infrastructure\CsvPOTranslator;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\ImageSourceFactory;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineColor;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineSize;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineType;
use Hoerbiger\Shared\Presentation\Block\MetaHeadline\MetaHeadline;
use Hoerbiger\Shared\Presentation\Block\MetaHeadline\MetaHeadlineVariant;
use Hoerbiger\Shared\Presentation\Block\Separator\Separator;
use Hoerbiger\Shared\Presentation\Block\Separator\SeparatorVariant;
use Hoerbiger\Shared\Presentation\Block\Text\Text;
use Hoerbiger\Shared\Presentation\Block\Text\TextVariant;
use Hoerbiger\Shared\Presentation\Layout\Stage\StackStage\StackStage;
use Hoerbiger\Shared\Presentation\Layout\Stage\StageVariant;
use Neos\ContentRepository\Domain\Model\Node;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Editable;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;

final class StageFactory extends AbstractComponentPresentationObjectFactory
{
    public function __construct(
        private readonly ImageSourceFactory $imageSourceFactory,
        private readonly FigureFactory $figureFactory,
        private readonly FormattedDateFactory $formattedDateFactory,
        private readonly CsvPOTranslator $csvPOTranslator
    ) {
    }

    public function forPressReleaseIntroduction(Node $pressRelease, bool $inBackend): StackStage
    {
        $imageSource = $this->imageSourceFactory->forImageMixin($pressRelease, $inBackend, true);
        $labels = $this->csvPOTranslator->getLabels(self::class);

        $pressVariant = PressVariant::tryFromNode($pressRelease);

        $datePublished = $pressRelease->getProperty('datePublished') ?: $pressRelease->getCreationDateTime();

        $showImage = $pressVariant !== PressVariant::VARIANT_NEWS || $pressRelease->getProperty('showImage');

        return new StackStage(
            $pressVariant === PressVariant::VARIANT_NEWS
                ? StageVariant::VARIANT_PRESS
                : StageVariant::VARIANT_PRESS_LARGE,
            new Separator(
                SeparatorVariant::VARIANT_FULL_WIDTH_WITH_CONTENT,
                new MetaHeadline(
                    MetaHeadlineVariant::VARIANT_REGULAR,
                    Value::fromString($labels['label.' . $pressVariant?->value] ?? ''),
                    $pressVariant === PressVariant::VARIANT_NEWS
                        ? Value::fromString('_' . $this->formattedDateFactory->createFullDate($datePublished))
                        : null,
                    $pressVariant !== PressVariant::VARIANT_NEWS
                        ? Value::fromString(
                            $pressRelease->getProperty('edition')
                                ? '_' .$pressRelease->getProperty('edition')
                                : ''
                        )
                        : null
                )
            ),
            new Headline(
                $pressVariant == PressVariant::VARIANT_NEWS
                    ? HeadlineSize::SIZE_TINY
                    : HeadlineSize::SIZE_LARGE,
                HeadlineColor::COLOR_TEXT,
                HeadlineType::TYPE_H1,
                Editable::fromNodeProperty($pressRelease, 'headline', false)
            ),
            null,
            $pressVariant !== PressVariant::VARIANT_NEWS
                ? $pressRelease->getProperty('abstract') || $inBackend
                    ? new Text(
                        TextVariant::VARIANT_LIGHT_CONTRAST,
                        Editable::fromNodeProperty($pressRelease, 'abstract'),
                        false
                    )
                    : null
                : null,
            $imageSource && $showImage
                ? $this->figureFactory->forStackStage($imageSource)
                : null,
            null
        );
    }
}
