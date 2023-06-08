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

#[Flow\Proxy(false)]
final class ContactForm extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly string $csrfProtectionToken,
        public readonly string $language,
        public readonly Uri $targetUri,
        /** @var array<string,array<int,CustomerGroupIdentifier|NodeAggregateIdentifier|Salutation|string>> */
        public readonly array $options,
        /** @var array<string,string> */
        public readonly array $labels,
        public readonly SlotInterface $successContent,
        public readonly SlotInterface $failContent,
        public readonly bool $isInBackend
    ) {
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
}
