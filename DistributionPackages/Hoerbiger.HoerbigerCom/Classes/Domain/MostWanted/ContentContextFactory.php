<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Domain\MostWanted;

/*
 * This script belongs to the "Hoerbiger.HoerbigerCom" package.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Neos\Domain\Repository\SiteRepository;
use Neos\Neos\Domain\Service\ContentContext;
use Neos\Neos\Domain\Service\ContentContextFactory as CoreContentContextFactory;

/**
 * The content context factory for Most Wanted concerns
 *
 * @Flow\Scope("singleton")
 */
final class ContentContextFactory
{
    private CoreContentContextFactory $coreContentContextFactory;

    private SiteRepository $siteRepository;

    public function __construct(CoreContentContextFactory $coreContentContextFactory, SiteRepository $siteRepository)
    {
        $this->coreContentContextFactory = $coreContentContextFactory;
        $this->siteRepository = $siteRepository;
    }

    public function create(string $language): ContentContext
    {
        /** @var ContentContext $contentContext */
        $contentContext = $this->coreContentContextFactory->create([
            'workspaceName' => 'live',
            'dimensions' => $language === 'en' ? ['language' => ['en']] : ['language' => [$language, 'en']],
            'targetDimensions' => ['language' => $language],
            'currentSite' => $this->siteRepository->findOneByNodeName('hoerbiger-hoerbigercom')
        ]);

        return $contentContext;
    }
}
