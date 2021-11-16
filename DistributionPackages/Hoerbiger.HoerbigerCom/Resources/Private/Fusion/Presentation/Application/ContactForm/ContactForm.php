<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Presentation\Application\ContactForm;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use GuzzleHttp\Psr7\Uri;
use Hoerbiger\HoerbigerCom\Domain\Contact\CustomerGroupIdentifier;
use Neos\ContentRepository\Domain\NodeAggregate\NodeAggregateIdentifier;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;

/**
 * @Flow\Proxy(false)
 */
final class ContactForm extends AbstractComponentPresentationObject implements ContactFormInterface
{
    private string $language;

    private Uri $targetUri;

    /**
     * @var array<string,array<int,CustomerGroupIdentifier|NodeAggregateIdentifier|string>>
     */
    private array $options;

    /**
     * @var array<string,string>
     */
    private array $labels;

    /**
     * @param array<string,array<int,CustomerGroupIdentifier|NodeAggregateIdentifier|string>> $options
     * @param array<string,string> $labels
     */
    public function __construct(
        string $language,
        Uri $targetUri,
        array $options,
        array $labels
    ) {
        $this->language = $language;
        $this->targetUri = $targetUri;
        $this->options = $options;
        $this->labels = $labels;
    }

    public function getApplicationData(): string
    {
        return json_encode([
            'language' => $this->language,
            'targetUri' => (string)$this->targetUri,
            'options' => $this->options,
            'labels' => $this->labels
        ]) ?: '{}';
    }
}
