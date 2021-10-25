<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\SiteFooter;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use Hoerbiger\Shared\Presentation\Block\Link\LinkFactory;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Neos\Domain\Service\ContentContext;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;


final class SiteFooterFactory extends AbstractComponentPresentationObjectFactory
{
    /**
     * @Flow\InjectConfiguration(path="socialMedia")
     * @var array<string,string>
     */
    protected $socialMediaSettings;

    public function forSubGraph(ContentContext $subGraph): SiteFooterInterface
    {
        $siteNode = $subGraph->getCurrentSiteNode();
        $linkFactory = new LinkFactory();
        return new SiteFooter(
            Collection::fromIterable($this->socialMediaSettings,
                function($socialMediaItem) use($linkFactory) {
                return $linkFactory->forSocialMediaIcon(
                    key($socialMediaItem),
                    reset($socialMediaItem)
                    );
                }
            ),
            Collection::fromIterable(
                $siteNode->getProperty('footerMenu') ?: [],
                function(TraversableNodeInterface $footerLinkNode) use ($linkFactory) {
                    return $linkFactory->forFooterMenuItem($footerLinkNode);
                }
            )
        );
    }
}
