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

final class SiteFooterFactory extends AbstractComponentPresentationObjectFactory
{
    /**
     * @Flow\InjectConfiguration(path="socialMedia")
     * @var array<string,mixed>
     */
    protected $socialMediaSettings;

    private ContactFormLinkFactory $contactFormLinkFactory;

    private LinkFactory $linkFactory;

    public function __construct(ContactFormLinkFactory $contactFormLinkFactory, LinkFactory $linkFactory)
    {
        $this->contactFormLinkFactory = $contactFormLinkFactory;
        $this->linkFactory = $linkFactory;
    }

    public function forDocument(TraversableNodeInterface $document, TraversableNodeInterface $site): SiteFooter
    {
        $socialMediaLinks = Collection::fromIterable(
            $this->socialMediaSettings,
            fn ($socialMediaItem, $index) => $this->linkFactory->forSocialMediaIcon(
                $index,
                $socialMediaItem["url"],
                $socialMediaItem["label"]
            )
        );

        return new SiteFooter(
            $this->uriService->getNodeUri($site),
            $this->uriService->getResourceUri('Hoerbiger.Shared', 'Images/logo_white.svg'),
            Collection::fromIterable(
                $site->getProperty('primaryFooter') ?: [],
                fn (TraversableNodeInterface $footerLinkNode) => $this->linkFactory->forFooterMenuItem($footerLinkNode)
            ),
            Collection::fromIterable(
                $site->getProperty('secondaryFooter') ?: [],
                fn (TraversableNodeInterface $footerLinkNode) => $this->linkFactory->forFooterMenuItem($footerLinkNode)
            ),
            Collection::fromIterable(
                $site->getProperty('tertiaryFooter') ?: [],
                fn (TraversableNodeInterface $footerLinkNode) => $this->linkFactory->forFooterMenuItem($footerLinkNode)
            ),
            $socialMediaLinks,
            $this->contactFormLinkFactory->forDocument($document, $site)
        );
    }
}
