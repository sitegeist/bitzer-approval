<?php

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Domain\Lifecycle\AutoTranslationReview;

use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Flow\Annotations as Flow;
use Psr\Http\Message\UriInterface;
use Sitegeist\Bitzer\Domain\Task\ActionStatusType;
use Sitegeist\Bitzer\Domain\Task\TaskIdentifier;
use Sitegeist\Bitzer\Domain\Task\TaskInterface;
use Sitegeist\Bitzer\Domain\Agent\Agent;

/**
 * The automatic translation review task domain entity
 */
#[Flow\Proxy(false)]
final class AutoTranslationReviewTask implements TaskInterface
{
    /**
     * @param array<string,mixed> $properties
     */
    public function __construct(
        private readonly TaskIdentifier $identifier,
        private readonly array $properties,
        private readonly \DateTimeImmutable $scheduledTime,
        private readonly ActionStatusType $actionStatus,
        private readonly Agent $agent,
        private readonly ?TraversableNodeInterface $object,
        private readonly ?UriInterface $target
    ) {
    }

    public static function getShortType(): string
    {
        return 'autotranslation-review';
    }


    public function getIdentifier(): TaskIdentifier
    {
        return $this->identifier;
    }

    /**
     * @inheritDoc
     */
    public function getImage(): string
    {
        return 'clipboard';
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return $this->properties['description'] ?? '';
    }

    /**
     * @inheritDoc
     */
    public function getScheduledTime(): \DateTimeImmutable
    {
        return $this->scheduledTime;
    }

    /**
     * @inheritDoc
     */
    public function getActionStatus(): ActionStatusType
    {
        return $this->actionStatus;
    }

    /**
     * @inheritDoc
     */
    public function getAgent(): Agent
    {
        return $this->agent;
    }

    /**
     * @inheritDoc
     */
    public function getObject(): ?TraversableNodeInterface
    {
        return $this->object;
    }

    /**
     * @inheritDoc
     */
    public function getTarget(): ?UriInterface
    {
        return $this->target;
    }

    /**
     * @return array<string,mixed>
     */
    public function getProperties(): array
    {
        return $this->properties;
    }
}
