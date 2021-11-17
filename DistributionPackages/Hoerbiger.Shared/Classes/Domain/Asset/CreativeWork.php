<?php declare(strict_types=1);

namespace Hoerbiger\Shared\Domain\Asset;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;

/**
 * The creative work domain value object
 *
 * @Flow\Proxy(false)
 */
final class CreativeWork
{
    private ?string $abstract;

    private ?string $copyrightHolder;

    private \DateTimeImmutable $dateModified;

    private \DateTimeImmutable $datePublished;

    private ?string $headline;

    /**
     * @var array<int,string>
     */
    private array $keywords;

    private string $text;

    private string $name;

    private ?string $description;

    /**
     * @param array<int,string> $keywords
     */
    public function __construct(
        ?string $abstract,
        ?string $copyrightHolder,
        \DateTimeImmutable $dateModified,
        \DateTimeImmutable $datePublished,
        ?string $headline,
        array $keywords,
        string $text,
        string $name,
        ?string $description
    ) {
        $this->abstract = $abstract;
        $this->copyrightHolder = $copyrightHolder;
        $this->dateModified = $dateModified;
        $this->datePublished = $datePublished;
        $this->headline = $headline;
        $this->keywords = $keywords;
        $this->text = $text;
        $this->name = $name;
        $this->description = $description;
    }

    public function getAbstract(): ?string
    {
        return $this->abstract;
    }

    public function getCopyrightHolder(): ?string
    {
        return $this->copyrightHolder;
    }

    public function getDateModified(): \DateTimeImmutable
    {
        return $this->dateModified;
    }

    public function getDatePublished(): \DateTimeImmutable
    {
        return $this->datePublished;
    }

    public function getHeadline(): ?string
    {
        return $this->headline;
    }

    /**
     * @return array<int,string>
     */
    public function getKeywords(): array
    {
        return $this->keywords;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
}
