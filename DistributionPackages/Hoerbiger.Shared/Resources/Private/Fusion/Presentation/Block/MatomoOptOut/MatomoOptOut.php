<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\MatomoOptOut;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use GuzzleHttp\Psr7\Uri;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Psr\Http\Message\UriInterface;

#[Flow\Proxy(false)]
final class MatomoOptOut extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly UriInterface $src
    ) {
    }
    public static function forLocale(string $locale): self
    {
        return new self(new Uri('https://piwik.hoerbiger.com/index.php?module=CoreAdminHome&action=optOut&language=' . $locale . '&backgroundColor=&fontColor=000000&fontSize=&fontFamily=bzw'));
    }
}
