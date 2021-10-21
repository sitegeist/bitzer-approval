<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Presentation\EnumProvider\Introduction;

/*
 * This file is part of the MeisterWerke.MeisterwerkeCom package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;
use Hoerbiger\Shared\Presentation\Layout\Stage\StageVariant as Source;

/**
 * @Flow\Proxy(false)
 */
final class StageVariant implements PseudoEnumInterface
{

    /**
     * @return array<int,Source>|Source[]
     */
    public static function cases(): array
    {
        return [
            Source::fullMediaTextLeft(),
            Source::fullMediaTextRight()
        ];
    }

    public function getValue(): string
    {
        return '';
    }
}
