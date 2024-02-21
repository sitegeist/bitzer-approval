<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\HR\Presentation\Layout\Page;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.HR package.
 */

use Hoerbiger\HoerbigerCom\HR\Presentation\Block\Placeholder\PlaceholderFactory;
use Hoerbiger\Shared\Presentation\Block\SiteHeader\SiteHeaderFactory;
use Hoerbiger\Shared\Presentation\Block\Breadcrumb\BreadcrumbFactory;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainer;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainerVariant;
use Hoerbiger\Shared\Presentation\Layout\Page\Page;
use Hoerbiger\Shared\Presentation\Layout\Stage\StageFactory;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Flow\Mvc\ActionRequest;
use Neos\Neos\Domain\Service\ContentContext;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

final class PageFactory extends AbstractComponentPresentationObjectFactory
{

    public function __construct(
        private readonly StageFactory $stageFactory,
        private readonly SiteHeaderFactory $siteHeaderFactory,
        private readonly PlaceholderFactory $placeholderFactory,
        private readonly BreadcrumbFactory $breadcrumbFactory,
    ) {
    }

    public function forJobList(
        TraversableNodeInterface $site,
        TraversableNodeInterface $jobList,
        bool $inBackend,
        ContentContext $subGraph
    ): Page {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        /** @var SlotInterface $breadcrumb */
        $breadcrumb = $this->breadcrumbFactory->forPage($jobList, $subGraph);

        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forHeaderStage($jobList, $subGraph, $inBackend);

        return new Page(
            $this->siteHeaderFactory->forDocument($jobList, $site, $subGraph, null),
            Collection::fromIterable(array_filter([
                new ContentContainer(
                    ContentContainerVariant::VARIANT_BLANK_BOTTOM,
                    $stage
                ),
                new ContentContainer(
                    ContentContainerVariant::VARIANT_REGULARX,
                    $this->placeholderFactory->forJobList()
                ),
                new ContentContainer(
                    ContentContainerVariant::VARIANT_REGULARX,
                    $breadcrumb
                )
            ]), $identity)
        );
    }

    public function forJob(
        TraversableNodeInterface $site,
        TraversableNodeInterface $job,
        ContentContext $subGraph,
        ActionRequest $request
    ): Page {
        $content = new ContentContainer(
            ContentContainerVariant::VARIANT_REGULARX,
            $this->placeholderFactory->forJob()
        );

        return $this->forDocumentWithoutIntroduction(
            $site,
            $job,
            $content,
            $subGraph,
            $request
        );
    }

    public function forJobNotification(
        TraversableNodeInterface $site,
        TraversableNodeInterface $jobNotification,
        ContentContext $subGraph,
        ActionRequest $request
    ): Page {
        $content = new ContentContainer(
            ContentContainerVariant::VARIANT_REGULARX,
            $this->placeholderFactory->forJobNotification()
        );

        return $this->forDocumentWithoutIntroduction(
            $site,
            $jobNotification,
            $content,
            $subGraph,
            $request
        );
    }

    public function forJobApplication(
        TraversableNodeInterface $site,
        TraversableNodeInterface $jobApplication,
        ContentContext $subGraph,
        ActionRequest $request
    ): Page {
        $content = new ContentContainer(
            ContentContainerVariant::VARIANT_REGULARX,
            $this->placeholderFactory->forJobApplication()
        );

        return $this->forDocumentWithoutIntroduction($site,$jobApplication,$content,$subGraph, $request);
    }

    public function forDocumentWithoutIntroduction(
        TraversableNodeInterface $site,
        TraversableNodeInterface $document,
        SlotInterface $content,
        ContentContext $subGraph,
        ActionRequest $request
    ): Page {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        /** @var SlotInterface $breadcrumb */
        $breadcrumb = $this->breadcrumbFactory->forPage($document, $subGraph);
        return new Page(
            $this->siteHeaderFactory->forDocument($document, $site, $subGraph, $request),
            Collection::fromIterable(array_filter([
                $content,
                new ContentContainer(
                    ContentContainerVariant::VARIANT_REGULARX,
                    $breadcrumb
                )
            ]), $identity)
        );
    }
}
