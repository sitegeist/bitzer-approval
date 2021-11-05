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
    private string $labels;

    private string $articles;

    public function __construct(
        string $labels,
        string $articles
    ) {
        $this->labels = $labels;
        $this->articles = $articles;
    }

    public function getLabels(): string
    {
        return $this->labels;
    }

    public function getArticles(): string
    {
        return $this->articles;
    }
}
