<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Application\CompactSearchForm;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\Block\Icon\IconInterface;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Psr\Http\Message\UriInterface;

/**
 * @Flow\Proxy(false)
 */
final class CompactSearchForm extends AbstractComponentPresentationObject implements CompactSearchFormInterface
{
    private UriInterface $targetUri;

    private IconInterface $searchIcon;

    private string $labels;

    public function __construct(
        UriInterface $targetUri,
        IconInterface $searchIcon,
        string $labels
    ) {
        $this->targetUri = $targetUri;
        $this->searchIcon = $searchIcon;
        $this->labels = $labels;
    }

    public function getTargetUri(): UriInterface
    {
        return $this->targetUri;
    }

    public function getSearchIcon(): IconInterface
    {
        return $this->searchIcon;
    }

    public function getLabels(): string
    {
        return $this->labels;
    }
}
