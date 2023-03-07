<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\PressArchive\Presentation\Layout\Stage;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.PressArchive package.
 */

use Hoerbiger\HoerbigerCom\PressArchive\Presentation\EnumProvider\Press\PressVariant;
use Hoerbiger\HoerbigerCom\PressArchive\Presentation\EnumProvider\Press\PressVariantIsInvalid;
use Hoerbiger\Shared\Domain\FormattedDateFactory;
use Hoerbiger\Shared\Infrastructure\CsvPOTranslator;
use Hoerbiger\Shared\Presentation\Block\Card\PressReleaseCard\PressReleaseCardVariant;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\ImageSourceFactory;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineColor;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineSize;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineType;
use Hoerbiger\Shared\Presentation\Block\MetaHeadline\MetaHeadline;
use Hoerbiger\Shared\Presentation\Block\MetaHeadline\MetaHeadlineVariant;
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
    private CsvPOTranslator $csvPOTranslator;

    public function __construct(
        ImageSourceFactory $imageSourceFactory,
        FigureFactory $figureFactory,
        FormattedDateFactory $formattedDateFactory,
        CsvPOTranslator $csvPOTranslator
    ) {
        $this->imageSourceFactory = $imageSourceFactory;
        $this->figureFactory = $figureFactory;
        $this->formattedDateFactory = $formattedDateFactory;
        $this->csvPOTranslator = $csvPOTranslator;
    }

    public function forPressReleaseIntroduction(TraversableNodeInterface $pressRelease, bool $inBackend): StageInterface
    {
        $imageSource = $this->imageSourceFactory->forImageMixin($pressRelease, $inBackend, true);
        $labels = $this->csvPOTranslator->getLabels(self::class);

        /** @var PressReleaseCardVariant $pressVariant */
        $pressVariant = PressVariant::from('news');

        switch ((string)$pressRelease->getNodeType()) {
            case 'Hoerbiger.HoerbigerCom.PressArchive:Document.HannsRelease':
                $pressVariant = PressVariant::from('hanns');
                break;
            case 'Hoerbiger.HoerbigerCom.PressArchive:Document.YearbookRelease':
                $pressVariant = PressVariant::from('yearbook');
                break;
            case 'Hoerbiger.HoerbigerCom.PressArchive:Document.PressRelease':
                $pressVariant = PressVariant::from('news');
                break;
            default:
                throw PressVariantIsInvalid::becauseItMustBeOneOfTheDefinedConstants((string)$pressRelease->getNodeType());
        }


        /** @var Node $pressRelease */
        $datePublished = $pressRelease->getProperty('datePublished') ?: $pressRelease->getCreationDateTime();
        /** @var TraversableNodeInterface $pressRelease */

        return new StackStage(
            StageVariant::stack(),
            new MetaHeadline(
                MetaHeadlineVariant::regular(),
                Value::fromString($labels['label.' . $pressVariant->getValue()]),
                Value::fromString($pressVariant->getIsNews()
                    ? '_' . $this->formattedDateFactory->createFullDate($datePublished)
                    : 'Compression_'
                ),
                !$pressVariant->getIsNews()
                    ? Value::fromString($pressRelease->getProperty('edition'))
                    : null
            ),
            new Headline(
                HeadlineSize::superLarge(),
                HeadlineColor::brand(),
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
