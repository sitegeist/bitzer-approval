<?php

declare(strict_types=1);

namespace Hoerbiger\Shared\Application;

use Neos\ContentRepository\Domain\Model\NodeType;
use Neos\ContentRepository\NodeTypePostprocessor\NodeTypePostprocessorInterface;
use Neos\Flow\I18n\Locale;

final class CountryCodeProvider implements NodeTypePostprocessorInterface
{
    public function __construct(
        private readonly CountryDirectory $countryDirectory,
    ) {
    }

    /**
     * @param NodeType $nodeType
     * @param array<string, mixed> $configuration
     * @param array<string, mixed> $options
     */
    public function process(NodeType $nodeType, array &$configuration, array $options): void
    {
        if (!array_key_exists('propertyNames', $options) || !is_array($options['propertyNames'])) {
            throw new \InvalidArgumentException('Option "propertyNames" must be provided.', 1764324076);
        }

        $countries = iterator_to_array($this->countryDirectory->findAll(new Locale('en')));
        uasort($countries, function (Country $a, Country $b) {
            return $a->getName() <=> $b->getName();
        });
        $values = array_map(
            fn (Country $country): array => [
                'label' => $country->getName(),
            ],
            $countries,
        );
        foreach ($options['propertyNames'] as $propertyName) {
            $configuration['properties'][$propertyName]['ui']['inspector']['editorOptions']['values'] = $values;
        }
    }
}
