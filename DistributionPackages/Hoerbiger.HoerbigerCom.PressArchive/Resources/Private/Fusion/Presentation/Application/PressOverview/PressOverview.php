<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\PressArchive\Presentation\Application\PressOverview;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.PressArchive package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

/**
 * @Flow\Proxy(false)
 */
final class PressOverview extends AbstractComponentPresentationObject implements PressOverviewInterface
{
    private SlotInterface $cards;

    /**
     * @var array<string,string>
     */

    private array $labels;

    /**
     * @param array<string,string> $labels
     */
    public function __construct(
        array $labels,
        SlotInterface $cards
    ) {
        $this->labels = $labels;
        $this->cards = $cards;
    }
    public function getCards(): SlotInterface
    {
        return $this->cards;
    }

    /**
     * @return array<string,string>
     */
    public function getLabels(): array
    {
        return $this->labels;
    }
}
