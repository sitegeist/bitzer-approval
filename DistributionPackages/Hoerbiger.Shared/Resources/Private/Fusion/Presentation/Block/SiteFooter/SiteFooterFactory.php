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
use Hoerbiger\Shared\Integration\CacheSegment\CacheSegment;


final class SiteFooterFactory extends AbstractComponentPresentationObjectFactory
{
    /**
     * @Flow\InjectConfiguration(path="socialMedia")
     * @var array<string,array>
     */
    protected $socialMediaSettings;

    private ContactFormLinkFactory $contactFormLinkFactory;

    public function __construct(ContactFormLinkFactory $contactFormLinkFactory)
    {
        $this->contactFormLinkFactory = $contactFormLinkFactory;
    }

    public function forDocument(TraversableNodeInterface $document, TraversableNodeInterface $site): SiteFooterInterface
    {
        $linkFactory = new LinkFactory();

        return new SiteFooter(
            Collection::fromIterable(
                $this->socialMediaSettings,
                function($socialMediaItem, $index) use($linkFactory) {
                    return $linkFactory->forSocialMediaIcon(
                        $index,
                        $socialMediaItem["url"],
                        $socialMediaItem["label"]
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
                'Hoerbiger.Shared:CacheSegment.SiteReactive'
            ),
            $this->contactFormLinkFactory->forDocument($document, $site)
        );
    }
}
