<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\ApplicationWorlds\Presentation\Block\IntroSection;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.ApplicationWorlds package.
 */

use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Neos\ContentRepository\Domain\NodeType\NodeTypeConstraints;
use Neos\ContentRepository\Domain\NodeType\NodeTypeName;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;

final class IntroSectionFactory extends AbstractComponentPresentationObjectFactory
{
    public function forSliderIntroSection(TraversableNodeInterface $node,NodeTypeName $targetNodeTypeName ,bool $inBackend): SlotInterface
    {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        $targetNodes = $node->findChildNodes(
            new NodeTypeConstraints(
                false,
                [$targetNodeTypeName]
            ));

        return Collection::fromNodes($targetNodes,function($targetNode) use ($inBackend, $identity) {
            /** @var TraversableNodeInterface $targetNode */
            $documentHeadline = null;

            if($targetNode->getProperty('preview__headline')) {
                $documentHeadline = Value::fromString($targetNode->getProperty('preview__headline'));
            } elseif ($targetNode->getProperty('headline')) {
                $documentHeadline = Value::fromString($targetNode->getProperty('headline'));
            }

            $documentDescription = $targetNode->getProperty('preview__description') ? Value::fromString($targetNode->getProperty('preview__description')) : null;

            return Collection::fromIterable(array_filter([
                (new FigureFactory())->forPreviewImageMixin($targetNode, $inBackend),
                (new FigureFactory())->forImageProperty($targetNode, $inBackend, true, 'preview__icon'),
                $documentHeadline,
                $documentDescription
                ]
            ),$identity);
        });
    }

    public function forApplicationWorld(TraversableNodeInterface $documents, bool $inBackend): SlotInterface
    {
        return Value::fromString('to be implemented');
    }
}
