<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\SiteHeader;

/*
 * This file is part of the Hoerbiger.Shared package.
 */


use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Hoerbiger\Shared\Presentation\Block\Link\LinkFactory;
use Hoerbiger\Shared\Presentation\Block\Navigation\NavigationFactory;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;

final class SiteHeaderFactory extends AbstractComponentPresentationObjectFactory
{
    private NavigationFactory $navigationFactory;

    public function __construct(
        NavigationFactory $navigationFactory
    ) {
        $this->navigationFactory = $navigationFactory;
    }

    public function forDocument(TraversableNodeInterface $document, TraversableNodeInterface $site): SiteHeaderInterface
    {
        return new SiteHeader(
            $this->navigationFactory->forMainNavigation($site, $document),
            Value::fromString('Search'),
            Value::fromString('Language Navi')
        );
    }
}
