<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\SuccessStories\Presentation\Application\ArticleOverview;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.SuccessStories package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;

/**
 * @Flow\Proxy(false)
 */
final class ArticleOverview extends AbstractComponentPresentationObject implements ArticleOverviewInterface
{
    private string $applicationData;

    public function __construct(
        string $applicationData
    ) {
        $this->applicationData = $applicationData;
    }

    public function getApplicationData(): string
    {
        return $this->applicationData;
    }
}
