<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Domain;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Utility\Algorithms;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;

/**
 * @Flow\Scope("singleton")
 */
final class AnchorFactory extends AbstractComponentPresentationObjectFactory
{
    public function fromAnchorLabel(string $anchorLabel): Anchor
    {
        return new Anchor(
            $this->extractReadableIdentifierFromLabel($anchorLabel),
            $anchorLabel
        );
    }

    public function extractReadableIdentifierFromLabel(string $anchorLabel): string
    {
        $anchorID = \mb_strtolower($anchorLabel);
        $anchorID = str_replace(['ä','ü','ö','ß'], ['ae','ue','oe','ss'], $anchorID);
        $anchorID = preg_replace('/[^a-z0-9_\s-]/u', '', $anchorID) ?: '';
        $anchorID = preg_replace('/[\s_-]+/', ' ', $anchorID) ?: '';
        return preg_replace('/[\s_-]/', '-', $anchorID) ?: Algorithms::generateUUID();
    }
}
