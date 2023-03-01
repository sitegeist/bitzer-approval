<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Catalog\Calculator\Presentation\Application\MiniCalculator;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.Catalog.Calculator package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;

/**
 * @Flow\Proxy(false)
 */
final class MiniCalculator extends AbstractComponentPresentationObject implements MiniCalculatorInterface
{
    private string $content;

    public function __construct(
        string $content
    ) {
        $this->content = $content;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
