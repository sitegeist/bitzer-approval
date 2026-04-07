<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\SiteFooter;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Neos\Domain\Model\Site;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;

final class SiteFooterSimpleFactory extends AbstractComponentPresentationObjectFactory
{
    /**
     * @Flow\InjectConfiguration(path="socialMedia")
     * @var array<string,mixed>
     */
    protected $socialMediaSettings;

    public function __construct() {
    }

    public function forDocument(TraversableNodeInterface $document, TraversableNodeInterface $site): SiteFooterSimple
    {

        return new SiteFooterSimple(
            $this->uriService->getNodeUri($site),
            $this->uriService->getResourceUri('Hoerbiger.Shared', 'Images/logo_white.svg')
        );
    }
}
