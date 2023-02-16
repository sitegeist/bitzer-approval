<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Catalog\Calculator\Presentation\Application\MiniCalculator;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.Catalog.Calculator package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;

interface MiniCalculatorInterface extends ComponentPresentationObjectInterface
{
    public function getContent(): string;
}
