<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Catalog\Calculator\Presentation\Application\MiniCalculator;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.Catalog.Calculator package.
 */

use Neos\Flow\ResourceManagement\ResourceManager;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;

final class MiniCalculatorFactory extends AbstractComponentPresentationObjectFactory
{
    /**
     * @Flow\Inject
     * @var ResourceManager
     */
    protected $resourceManager;

    public function create(): MiniCalculator
    {

        $content = file_get_contents(
            'resource://Hoerbiger.HoerbigerCom.Catalog.Calculator/Private/'
            . 'Fusion/Presentation/Application/MiniCalculator/miniCalculator.html'
        ) ?: '';

        $body = '<body class="miniCalculator_body">';
        $start = \mb_strpos($content, $body) + \mb_strlen($body);
        $end = \mb_strrpos($content, '</body>');
        $content = \mb_substr($content, $start, $end - $start);

        $baseUri = $this->resourceManager->getPublicPackageResourceUri(
            'Hoerbiger.HoerbigerCom.Catalog.Calculator',
            'Images/'
        );
        $content = \str_replace(
            [
                'src="images/'
            ], [
                'src="' . $baseUri
            ],
            $content
        );

        return new MiniCalculator($content);
    }
}
