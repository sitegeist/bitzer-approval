<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\Button;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\Block\Link\LinkFactory;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Neos\Domain\Service\ContentContext;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;

final class ButtonFactory extends AbstractComponentPresentationObjectFactory
{
    public function forLinkMixin(TraversableNodeInterface $node, ContentContext $contentContext): ?Button
    {
        $link = (new LinkFactory())->forLinkMixin($node, $contentContext);

        return $link
            ? new Button(
                ButtonType::link(),
                ButtonVariant::cta(),
                Value::fromString($node->getProperty('link__label') ?: ''),
                $link->getHref(),
                $link->getTarget()->getValue(),
                $link->getRel()
            )
            : null;
    }
}
