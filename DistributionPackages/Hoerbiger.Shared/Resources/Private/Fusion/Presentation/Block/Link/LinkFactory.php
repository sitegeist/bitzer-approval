<?php

declare(strict_types=1);

namespace Hoerbiger\Shared\Presentation\Block\Link;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use GuzzleHttp\Psr7\Uri;
use Hoerbiger\Shared\Domain\Anchor;
use Hoerbiger\Shared\Presentation\Block\Icon\Icon;
use Hoerbiger\Shared\Presentation\Block\Icon\IconColor;
use Hoerbiger\Shared\Presentation\EnumProvider\Theme\Theme;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Media\Domain\Model\Document;
use Neos\Neos\Domain\Service\ContentContext;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
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
                LinkVariant::tryFromTheme($theme) ?: LinkVariant::VARIANT_LABELDEFAULT,
                $href,
                $linkTarget,
                $linkTarget === LinkTarget::TARGET_BLANK ? 'noopener nofollow' : null,
                Value::fromString($node->getProperty('link__label') ?: ''),
                null
            )
            : null;
    }

    public function forLinkToDocument(TraversableNodeInterface $document, ?Theme $theme): Link
    {
        $linkTarget = LinkTarget::forNode($document);
        return new Link(
            LinkVariant::tryFromTheme($theme) ?: LinkVariant::VARIANT_ICONLABEL,
            $this->uriService->getNodeUri($document),
            $linkTarget,
            $linkTarget === LinkTarget::TARGET_BLANK ? 'noopener nofollow' : null,
            Value::fromString($document->getProperty("titleOverride") ?? $document->getLabel()),
            null
        );
    }

    public function forLinkToDocumentNavigationOverview(TraversableNodeInterface $document, string $labelContent, ?Theme $theme): Link
    {
        $linkTarget = LinkTarget::forNode($document);
        return new Link(
            LinkVariant::tryFromTheme($theme) ?: LinkVariant::VARIANT_LABEL,
            $this->uriService->getNodeUri($document),
            $linkTarget,
            $linkTarget === LinkTarget::TARGET_BLANK ? 'noopener nofollow' : null,
            Value::fromString($labelContent),
            null
        );
    }

    public function forAnchor(Anchor $anchor): Link {
        return new Link(
            LinkVariant::VARIANT_CONTAINER,
            new Uri('#' . $anchor->getIdentifier()),
            LinkTarget::TARGET_SELF,
            null,
            Value::fromString($anchor->getLabel()),
            null
        );
    }

    public function forBreadcrumbItem(TraversableNodeInterface $document): Link
    {
        return new Link(
            LinkVariant::VARIANT_NOSTYLES,
            $this->uriService->getNodeUri($document),
            LinkTarget::TARGET_SELF,
            null,
            Value::fromString($document->getLabel()),
            null
        );
    }

    public function forSocialMediaIcon(string $iconName, string $socialMediaLink, string $socialMediaLabel): Link
    {
        return new Link(
            LinkVariant::VARIANT_SQUARETOUCHICON,
            new Uri($socialMediaLink),
            LinkTarget::TARGET_SELF,
            null,
            new Icon('shared', strtolower($iconName), null, IconColor::COLOR_WHITE),
            $socialMediaLabel
        );
    }

    public function forFooterMenuItem(TraversableNodeInterface $document): Link
    {
        $linkTarget = LinkTarget::forNode($document);

        return new Link(
            LinkVariant::VARIANT_SITEFOOTER,
            $this->uriService->getNodeUri($document),
            $linkTarget,
            $linkTarget === LinkTarget::TARGET_BLANK ? 'noopener nofollow' : null,
            Value::fromString($document->getLabel()),
            null
        );
    }

    public function forLanguageMenuItem(Uri $documentUri, string $localeIdentifier): Link
    {
        return new Link(
            LinkVariant::VARIANT_TOUCHICON,
            $documentUri,
            LinkTarget::TARGET_SELF,
            null,
            Value::fromString($localeIdentifier),
            null
        );
    }

    public function forAsset(Document $asset): Link
    {
        return new Link(
            LinkVariant::VARIANT_CONTAINER,
            $this->uriService->getAssetUri($asset),
            LinkTarget::TARGET_SELF,
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

    public function forLinkWithContent(
        TraversableNodeInterface $node,
        ContentContext $subgraph,
        SlotInterface $content
    ): ?Link
    {
        $href = $this->resolveRawLinkTargetUri($node->getProperty('link__target'), $subgraph);
        $linkTarget = LinkTarget::forLinkPropertyValue($node->getProperty('link__target') ?: '');
        return $href
            ? new Link(
                LinkVariant::VARIANT_NOSTYLES,
                $href,
                $linkTarget,
                $linkTarget === LinkTarget::TARGET_BLANK ? 'noopener nofollow' : null,
                $content,
                null
            )
            : null;
    }
}
