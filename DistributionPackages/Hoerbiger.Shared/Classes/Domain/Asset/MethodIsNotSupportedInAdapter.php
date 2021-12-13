<?php declare(strict_types=1);

namespace Hoerbiger\Shared\Domain\Asset;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

/**
 * The exception to be thrown if a NodeInterface or TraversableNodeInterface method is called
 * that is not supported by node adapters
 */
final class MethodIsNotSupportedInAdapter extends \DomainException
{
    public static function becauseItIsAReadonlyAdapter(string $attemptedMethodName): self
    {
        return new self(
            'Method ' . $attemptedMethodName . ' is not supported by node adapters.',
            1588935795
        );
    }

    public static function becauseAdaptersEncapsulateNoNodeDataObject(string $attemptedMethodName): self
    {
        return new self(
            'Method ' . $attemptedMethodName
                . ' is not supported by node adapters because there is no node data object available.',
            1588948152
        );
    }
}
