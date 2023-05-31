<?php

declare(strict_types=1);

namespace Hoerbiger\Shared\Presentation\Layout\Stage;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\Block\Button\ButtonFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\ImageSourceFactory;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineColor;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineSize;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineType;
use Hoerbiger\Shared\Presentation\Layout\Stage\FullMediaStage\FullMediaStage;
use Hoerbiger\Shared\Presentation\Block\Text\Text;
use Hoerbiger\Shared\Presentation\Block\Text\TextVariant;
use Hoerbiger\Shared\Presentation\Layout\Stage\HeaderStage\HeaderStage;
use Hoerbiger\Shared\Presentation\Layout\Stage\VideoStage\VideoStage;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Neos\Domain\Service\ContentContext;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Editable;

final class StageFactory extends AbstractComponentPresentationObjectFactory
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

    public function forFullMediaIntroduction(TraversableNodeInterface $node, ContentContext $subGraph, bool $inBackend): StageInterface
    {
        $imageSource = $this->imageSourceFactory->forImageMixin($node, $inBackend, true);
        if (!$imageSource) {
            throw new \InvalidArgumentException('Introduction requires image for property media.');
        }
        $button = (new ButtonFactory())->forButtonMixin($node, $subGraph);

        return new FullMediaStage(
            StageVariant::from($node->getProperty('stage__variant') ?: StageVariant::VARIANT_FULLMEDIATEXTLEFT),
            null,
            new Headline(
                HeadlineSize::SIZE_SUPER_LARGE,
                HeadlineColor::COLOR_LITE_CONTRAST,
                HeadlineType::TYPE_H1,
                Editable::fromNodeProperty($node, 'headline')
            ),
            null,
            new Text(
                TextVariant::VARIANT_LITE_CONTRAST,
                Editable::fromNodeProperty($node, 'abstract'),
                false
            ),
            null,
            $this->figureFactory->forFullMediaStage($imageSource),
            $button
        );
    }

    public function forHeaderStage(TraversableNodeInterface $node, ContentContext $subGraph, bool $inBackend): StageInterface
    {
        $imageSource = $this->imageSourceFactory->forImageMixin($node, $inBackend, true);
        if (!$imageSource) {
            throw new \InvalidArgumentException('Introduction requires image for property media.');
        }
        $button = (new ButtonFactory())->forButtonMixin($node, $subGraph);

        return new HeaderStage(
            StageVariant::from($node->getProperty('stage__variant') ?: StageVariant::VARIANT_FULLMEDIATEXTLEFT),
            $node->getProperty('metaline') || $inBackend
                ? Editable::fromNodeProperty($node, 'metaline')
                : null,
            $node->getProperty('headline') || $inBackend
                ? new Headline(
                    HeadlineSize::SIZE_HEADER_STAGE,
                    HeadlineColor::COLOR_NEUTRAL_CONTRAST,
                    HeadlineType::TYPE_H1,
                    Editable::fromNodeProperty($node, 'headline')
                )
                : null,
            null,
            $node->getProperty('abstract') || $inBackend
                ? new Text(
                    TextVariant::VARIANT_LITE_CONTRAST,
                    Editable::fromNodeProperty($node, 'abstract'),
                    false
                )
                : null,
            null,
            $this->figureFactory->forFullMediaStage($imageSource),
            $button
        );
    }

    public function forVideoStageIntroduction(TraversableNodeInterface $node, bool $inBackend): ?VideoStage
    {
        $desktopVideoSource = $node->getProperty('video__source__desktop')
            ? $this->uriService->getAssetUri($node->getProperty('video__source__desktop'))
            : null;

        if (!$desktopVideoSource) {
            throw new \InvalidArgumentException('Introduction requires video for property media.');
        }

        $imageSource = $this->imageSourceFactory->forImageMixin($node, $inBackend, true);
        if (!$imageSource) {
            throw new \InvalidArgumentException('Introduction requires image for property media.');
        }

        return new VideoStage(
            StageVariant::default(),
            null,
            new Headline(
                HeadlineSize::SIZE_SUPER_LARGE,
                HeadlineColor::COLOR_LITE_CONTRAST,
                HeadlineType::TYPE_H1,
                Editable::fromNodeProperty($node, 'headline')
            ),
            null,
            new Text(
                TextVariant::VARIANT_LIGHT_CONTRAST,
                Editable::fromNodeProperty($node, 'text'),
                false
            ),
            null,
            null,
            null,
            $desktopVideoSource,
            $this->figureFactory->forFullMediaStage($imageSource)
        );
    }
}
