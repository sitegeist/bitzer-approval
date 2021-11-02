<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\SiteFooter;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\Block\ContactFormLink\ContactFormLinkFactory;
use Neos\Flow\Annotations as Flow;
use Hoerbiger\Shared\Presentation\Block\Link\LinkFactory;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use Hoerbiger\Shared\Presentation\Block\CacheSegment\CacheSegment;


final class SiteFooterFactory extends AbstractComponentPresentationObjectFactory
{
    /**
     * @Flow\InjectConfiguration(path="socialMedia")
     * @var array<string,string>
     */
    protected $socialMediaSettings;

    public function forDocument(TraversableNodeInterface $document, TraversableNodeInterface $site): SiteFooterInterface
    {
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
            new CacheSegment(
            Collection::fromIterable(
                $site->getProperty('footerMenu') ?: [],
                function(TraversableNodeInterface $footerLinkNode) use ($linkFactory) {
                    return $linkFactory->forFooterMenuItem($footerLinkNode);
                }
                ),
            'Hoerbiger.HoerbigerCom:CacheSegment.SiteReactive'
            ),
            (new ContactFormLinkFactory())->forDocument($document, $site)
        );
    }
}
