<?php

declare(strict_types=1);

namespace Hoerbiger\Shared\Presentation\Block\Link;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use GuzzleHttp\Psr7\Uri;
use Hoerbiger\Shared\Presentation\Block\Icon\Icon;
use Hoerbiger\Shared\Presentation\EnumProvider\Theme\Theme;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Media\Domain\Model\Document;
use Neos\Neos\Domain\Service\ContentContext;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;
use Psr\Http\Message\UriInterface;

final class LinkFactory extends AbstractComponentPresentationObjectFactory
{
    public function forLinkMixin(TraversableNodeInterface $node, ContentContext $subgraph, ?Theme $theme): ?Link
    {
        $href = $this->resolveRawLinkTargetUri($node->getProperty('link__target'), $subgraph);
        $linkTarget = LinkTarget::forLinkPropertyValue($node->getProperty('link__target') ?: '');
        return $href
            ? new Link(
                LinkVariant::tryFromTheme($theme) ?: LinkVariant::label(),
                $href,
                $linkTarget,
                $linkTarget->getIsBlank() ? 'noopener nofollow' : null,
                Value::fromString($node->getProperty('link__label')),
                null
            )
            : null;
    }

    public function forLinkToDocument(TraversableNodeInterface $document): Link
    {
        $linkTarget = LinkTarget::forNode($document);

        return new Link(
            LinkVariant::label(),
            $this->uriService->getNodeUri($document),
            $linkTarget,
            $linkTarget->getIsBlank() ? 'noopener nofollow' : null,
            Value::fromString($document->getProperty("titleOverride") ?? $document->getLabel()),
            null
        );
    }

    public function forBreadcrumbItem(TraversableNodeInterface $document): Link
    {
        return new Link(
            LinkVariant::noStyles(),
            $this->uriService->getNodeUri($document),
            LinkTarget::self(),
            null,
            Value::fromString($document->getLabel()),
            null
        );
    }

    public function forSocialMediaIcon(string $iconName, string $socialMediaLink, string $socialMediaLabel): Link
    {
        return new Link(
            LinkVariant::touchIcon(),
            new Uri($socialMediaLink),
            LinkTarget::self(),
            null,
            new Icon('shared', strtolower($iconName)),
            $socialMediaLabel
        );
    }

    public function forFooterMenuItem(TraversableNodeInterface $document): Link
    {
        $linkTarget = LinkTarget::forNode($document);

        return new Link(
            LinkVariant::siteFooter(),
            $this->uriService->getNodeUri($document),
            $linkTarget,
            $linkTarget->getIsBlank() ? 'noopener nofollow' : null,
            Value::fromString($document->getLabel()),
            null
        );
    }

    public function forLanguageMenuItem(Uri $documentUri, string $localeIdentifier): Link
    {
        return new Link(
            LinkVariant::touchIcon(),
            $documentUri,
            LinkTarget::self(),
            null,
            Value::fromString($localeIdentifier),
            null
        );
    }

    public function forAsset(Document $asset): Link
    {
        return new Link(
            LinkVariant::container(),
            $this->uriService->getAssetUri($asset),
            LinkTarget::blank(),
            null,
            null,
            null
        );
    }

    public function resolveRawLinkTargetUri(?string $rawLinkTarget, ContentContext $subgraph): ?UriInterface
    {
        $rawLinkTarget = $rawLinkTarget ?: '';
        if (\mb_substr($rawLinkTarget, 0, 7) === 'node://') {
            /** @var TraversableNodeInterface|null $targetNode */
            $targetNode = $subgraph->getNodeByIdentifier(\mb_substr($rawLinkTarget, 7));

            if ($targetNode) {
                return $this->uriService->getNodeUri($targetNode);
            }
        }

        if (\mb_substr($rawLinkTarget, 0, 4) === 'http') {
            return new Uri($rawLinkTarget);
        }

        return null;
    }
}
