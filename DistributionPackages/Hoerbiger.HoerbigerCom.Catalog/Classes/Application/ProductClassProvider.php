<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Catalog\Application;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.Catalog package.
 */

use Hoerbiger\HoerbigerCom\Catalog\Domain\ProductClass;
use Neos\ContentRepository\Domain\Model\NodeType;
use Neos\ContentRepository\NodeTypePostprocessor\NodeTypePostprocessorInterface;
use Neos\Flow\Annotations as Flow;

final class ProductClassProvider implements NodeTypePostprocessorInterface
{
    /**
     * @param NodeType $nodeType
     * @param array<string, mixed> $configuration
     * @param array<string, mixed> $options
     */
    public function process(NodeType $nodeType, array &$configuration, array $options)
    {
        foreach (ProductClass::cases() as $productClass) {
            $configuration['properties']['productClass']['ui']
                ['inspector']['editorOptions']['values'][$productClass->getClass()] = [
                    'label' => $productClass->getLabel()
                ];
        }
    }
}
