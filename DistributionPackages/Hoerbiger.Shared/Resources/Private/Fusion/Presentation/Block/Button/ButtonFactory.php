<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Button;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use GuzzleHttp\Psr7\Uri;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Neos\Domain\Service\ContentContext;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;
use Psr\Http\Message\UriInterface;

final class ButtonFactory extends AbstractComponentPresentationObjectFactory
{
    public function forButtonMixin(TraversableNodeInterface $node, ContentContext $subgraph): ?Button
    {
        $href = $this->resolveRawLinkTargetUri($node->getProperty('link__target'), $subgraph);

        return $href
            ? new Button(
                ButtonType::TYPE_LINK,
                ButtonVariant::VARIANT_CTA,
                Value::fromString($node->getProperty('link__label') ?: ''),
                $href,
                '_self',
                null
            )
            : null;
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
