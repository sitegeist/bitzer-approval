<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Link;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Neos\Domain\Service\ContentContext;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;
use Psr\Http\Message\UriInterface;

final class LinkFactory extends AbstractComponentPresentationObjectFactory
{
    public function forLinkMixin(TraversableNodeInterface $node, ContentContext $subgraph): ?Link
    {
        $href = $this->resolveRawLinkTargetUri($node->getProperty('link__target'), $subgraph);
        $linkTarget = LinkTarget::forLinkPropertyValue($node->getProperty('link__target') ?: '');

        return $href
            ? new Link(
                LinkVariant::label(),
                $href,
                $linkTarget,
                $linkTarget->getIsBlank() ? 'noopener nofollow' : null,
                Value::fromString($node->getProperty('link__label'))
            )
            : null;
    }

    public function forLinkToDocument(TraversableNodeInterface $document, ContentContext $subgraph): Link
    {
        $linkTarget = LinkTarget::forNode($document);

        return new Link(
            LinkVariant::label(),
            $this->uriService->getNodeUri($document),
            $linkTarget,
            $linkTarget->getIsBlank() ? 'noopener nofollow' : null,
            Value::fromString($document->getLabel())
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

        return null;
    }
}
