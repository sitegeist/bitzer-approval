<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\SuccessStories\Presentation\Application\ArticleOverview;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.SuccessStories package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;

interface ArticleOverviewInterface extends ComponentPresentationObjectInterface
{
    public function getLabels(): string;

    public function getArticles(): string;
}
