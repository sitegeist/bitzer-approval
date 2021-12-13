<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Domain\AssetSource;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Media\Domain\Model\AssetSource\AssetNotFoundExceptionInterface;
use Neos\Media\Exception;

final class LanguageAwareAssetNotFound extends Exception implements AssetNotFoundExceptionInterface
{
    /**
     * @var int
     */
    protected $statusCode = 404;
}
