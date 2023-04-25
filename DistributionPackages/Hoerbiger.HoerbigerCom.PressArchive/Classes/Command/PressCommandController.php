<?php

/*
 * This file is part of the Hoerbiger.HoerbigerCom.PressArchive package.
 */

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\PressArchive\Command;

use Neos\ContentRepository\Domain\Model\Node;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\Eel\FlowQuery\FlowQuery;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Cli\CommandController;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Neos\Domain\Service\ContentContext;
use Neos\Neos\Domain\Service\ContentContextFactory;
use Sitegeist\LostInTranslation\Domain\TranslationServiceInterface;

/**
 * @Flow\Scope("singleton")
 */
class PressCommandController extends CommandController
{
    /**
     * @Flow\Inject
     * @var ContentContextFactory
     */
    protected $contentContextFactory;



    public function copyNewsAbstractIntoPreviewCommand(): void
    {
        foreach (['de', 'en'] as $language) {
            $subgraph = $this->contentContextFactory->create([
                'dimensions' => [
                    'language' => ($language === 'de' ? ['de'] : ['en', 'de'])
                ],
                'targetDimensions' => ['language' => $language],
                'invisibleContentShown' => true,
                'removedContentShown' => false,
                'inaccessibleContentShown' => true
            ]);

            /** @var array<int,Node> $pressReleases */
            /** @phpstan-ignore-next-line magic method */
            $pressReleases = (new FlowQuery([$subgraph->getCurrentSiteNode()]))
                ->find('[instanceof Hoerbiger.HoerbigerCom.PressArchive:Document.PressRelease]')->get();

            foreach ($pressReleases as $pressRelease) {
                $pressRelease->setProperty(
                    'preview__description',
                    $pressRelease->getProperty('abstract') ?: ''
                );

                $pressRelease->setProperty('abstract', '');
            }
        }
    }
}
