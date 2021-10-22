<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\ApplicationWorlds\Presentation\Layout\Stage;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.ApplicationWorlds package.
 */

use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureObjectFit;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureObjectPosition;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineType;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineVariant;
use Hoerbiger\Shared\Presentation\Block\Text\Text;
use Hoerbiger\Shared\Presentation\Block\Text\TextVariant;
use Hoerbiger\Shared\Presentation\Layout\Stage\SlideStage\SlideStage;
use Hoerbiger\Shared\Presentation\Layout\Stage\StageInterface;
use Hoerbiger\Shared\Presentation\Layout\Stage\StageVariant;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;

final class StageFactory extends AbstractComponentPresentationObjectFactory
{
    public function forApplicationWorld(TraversableNodeInterface $documents, bool $inBackend): SlotInterface
    {
        return Value::fromString('to be implemented');
    }

    public function forApplicationWorldSlideStage(TraversableNodeInterface $targetNode, bool $inBackend): StageInterface
    {
        $documentHeadlineContent = null;

        if($targetNode->getProperty('slider__preview__headline')) {
            $documentHeadlineContent = Value::fromString($targetNode->getProperty('slider__preview__headline'));
        } elseif ($targetNode->getProperty('headline')) {
            $documentHeadlineContent = Value::fromString($targetNode->getProperty('headline'));
        }

        $textContent = $targetNode->getProperty('slider__preview__description')
            ? Value::fromString($targetNode->getProperty('slider__preview__description'))
            : null;

        $media = (new FigureFactory())->forImageProperty($targetNode, $inBackend, true, 'slider__preview__image');
        if ($media) {
            $media = $media->withObjectFitAndPosition(FigureObjectFit::contain(), FigureObjectPosition::center());
        }

        return new SlideStage(
            StageVariant::fullMediaTextRight(),
            $documentHeadlineContent
                ? new Headline(
                    HeadlineVariant::noBrand(),
                    HeadlineType::h2(),
                    $documentHeadlineContent
                )
                : null,
            null,
            $textContent
                ? new Text(
                    TextVariant::transparentContrast(),
                    $textContent,
                    false
                )
                : null,
                $media,
                (new FigureFactory())->forImageProperty($targetNode, $inBackend, true, 'slider__preview__background')
        );
    }
}
