<?php

/*
 * This file is part of the Hoerbiger.Shared package.
 */

declare(strict_types=1);

namespace Hoerbiger\Shared\Application;

use Neos\ContentRepository\Domain\Model\NodeType;
use Neos\ContentRepository\NodeTypePostprocessor\NodeTypePostprocessorInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\I18n\Translator;

final class IconProvider implements NodeTypePostprocessorInterface
{
    /**
     * @Flow\Inject
     * @var Translator
     */
    protected $translator;

    /**
     * @param NodeType $nodeType
     * @param array<mixed> $configuration
     * @param array<mixed> $options
     */
    public function process(NodeType $nodeType, array &$configuration, array $options): void
    {
        $icons = [
            'ausbildungsberufe',
            'ausbildungszentrum',
            'bewerbung',
            'daumen-hoch',
            'faqs',
            'jugendlicher-ueberlegt',
            'pc',
            'schnupperpraktikum',
            'warum-hoerbiger',
            'was-du-schon-gemacht-hast',
        ];
        foreach ($icons as $icon) {
            $configuration['properties']['icon']['ui']['inspector']['editorOptions']['values'][$icon] = [
                'label' => $this->translator->translateById(
                    'icon.' . $icon,
                    [],
                    null,
                    null,
                    'Icons',
                    'Hoerbiger.Shared'
                ) ?: $icon
            ];
        }
        $configuration['properties']['icon']['defaultValue'] = reset($icons);
    }
}
