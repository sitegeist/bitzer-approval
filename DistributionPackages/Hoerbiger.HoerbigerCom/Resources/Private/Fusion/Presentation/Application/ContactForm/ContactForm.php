<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Presentation\Application\ContactForm;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use GuzzleHttp\Psr7\Uri;
use Hoerbiger\HoerbigerCom\Domain\Contact\CustomerGroupIdentifier;
use Hoerbiger\HoerbigerCom\Domain\Contact\Salutation;
use Neos\ContentRepository\Domain\NodeAggregate\NodeAggregateIdentifier;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use PhpParser\Node\Expr\Cast\Bool_;

/**
 * @Flow\Proxy(false)
 */
final class ContactForm extends AbstractComponentPresentationObject implements ContactFormInterface
{
    private string $csrfProtectionToken;

    private string $language;

    private Uri $targetUri;

    /**
     * @var array<string,array<int,CustomerGroupIdentifier|NodeAggregateIdentifier|Salutation|string>>
     */
    private array $options;

    /**
     * @var array<string,string>
     */
    private array $labels;

    private SlotInterface $successContent;

    private SlotInterface $failContent;

    private bool $isInBackend;

    /**
     * @param array<string,array<int,CustomerGroupIdentifier|NodeAggregateIdentifier|Salutation|string>> $options
     * @param array<string,string> $labels
     */
    public function __construct(
        string $csrfProtectionToken,
        string $language,
        Uri $targetUri,
        array $options,
        array $labels,
        SlotInterface $successContent,
        SlotInterface $failContent,
        bool $isInBackend
    ) {
        $this->csrfProtectionToken = $csrfProtectionToken;
        $this->language = $language;
        $this->targetUri = $targetUri;
        $this->options = $options;
        $this->labels = $labels;
        $this->successContent = $successContent;
        $this->failContent = $failContent;
        $this->isInBackend = $isInBackend;
    }

    public function getApplicationData(): string
    {
        return json_encode([
            'csrfProtectionToken' => $this->csrfProtectionToken,
            'language' => $this->language,
            'targetUri' => (string)$this->targetUri,
            'options' => $this->options,
            'labels' => $this->labels
        ]) ?: '{}';
    }

    public function getSuccessContent(): SlotInterface
    {
        return $this->successContent;
    }

    public function getFailContent(): SlotInterface
    {
        return $this->failContent;
    }

    public function getIsInBackend(): bool
    {
        return $this->isInBackend;
    }
}
