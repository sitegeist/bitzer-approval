<?php declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Application\Controller;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use GuzzleHttp\Psr7\Uri;
use Hoerbiger\HoerbigerCom\Domain\MarketingUrl\DeleteMarketingUrl;
use Hoerbiger\HoerbigerCom\Domain\MarketingUrl\MarketingUrlRepository;
use Hoerbiger\HoerbigerCom\Domain\MarketingUrl\PathIsAlreadyOccupied;
use Hoerbiger\HoerbigerCom\Domain\MarketingUrl\PathIsNotYetOccupied;
use Hoerbiger\HoerbigerCom\Domain\MarketingUrl\RegisterMarketingUrl;
use Hoerbiger\HoerbigerCom\Domain\MarketingUrl\UpdateMarketingUrl;
use Neos\ContentRepository\Domain\Model\Node;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\ContentRepository\Domain\Service\ContextFactoryInterface;
use Neos\Eel\FlowQuery\FlowQuery;
use Neos\Error\Messages\Message;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\View\ViewInterface;
use Neos\Flow\Security\Context as SecurityContext;
use Neos\Neos\Controller\Module\AbstractModuleController;
use Neos\Neos\Domain\Service\ContentContext;
use Sitegeist\Bitzer\Infrastructure\FusionView;

/**
 * @Flow\Scope("singleton")
 */
class MarketingUrlManagementController extends AbstractModuleController
{
    /**
     * @var string
     */
    protected $defaultViewObjectName = FusionView::class;

    /**
     * @var FusionView
     */
    protected $view;

    /**
     * @Flow\Inject
     * @var MarketingUrlRepository
     */
    protected $marketingUrlRepository;

    /**
     * @Flow\Inject
     * @var ContextFactoryInterface
     */
    protected $contentContextFactory;

    /**
     * @Flow\Inject
     * @var SecurityContext
     */
    protected $securityContext;

    public function indexAction()
    {
        $subgraph = $this->getSubgraph();
        $marketingUrls = [];
        foreach ($this->marketingUrlRepository->findAll() as $key => $marketingUrl) {
            /** @var ?NodeInterface $node */
            $node = $subgraph->getNodeByIdentifier((string)$marketingUrl->getNodeAggregateIdentifier());
            $marketingUrls[] = [
                'identifier' => '-id-'. $key,
                'path' => $marketingUrl->getPath(),
                'label' => $node ? $node->getLabel() : 'Unbekannter Knoten',
                'updateFormUri' => $this->getActionUri(
                    'updateForm',
                    ['path' => $marketingUrl->getPath()]
                )
            ];
        }

        $this->view->assignMultiple([
            'createUri' => $this->getActionUri('creationForm'),
            'deleteUri' => $this->getActionUri('delete'),
            'marketingUrls' => $marketingUrls,
            'csrfProtectionToken' => $this->securityContext->getCsrfProtectionToken()
        ]);
    }

    public function creationFormAction(): void
    {
        $this->view->assignMultiple([
            'creationUri' => $this->getActionUri('create'),
            'documentOptions' => $this->findDocumentOptions(),
            'csrfProtectionToken' => $this->securityContext->getCsrfProtectionToken()
        ]);
    }

    /**
     * @phpstan-param array<string,string> $command
     */
    public function createAction(array $command): void
    {
        $domainCommand = RegisterMarketingUrl::fromArray($command);
        try {
            $this->marketingUrlRepository->handleRegisterMarketingUrl($domainCommand);
            $this->addFlashMessage(
                'Marketing-URL "' . $domainCommand->getPath() . '" wurde angelegt.',
                'Aktion erfolgreich'
            );
            $this->redirect('index');
        } catch (PathIsAlreadyOccupied $exception) {
            $this->addFlashMessage(
                'Der Pfad "' . $domainCommand->getPath() . '" ist bereits belegt.',
                'Aktion gescheitert',
                Message::SEVERITY_ERROR
            );
            $this->redirect('creationForm');
        }
    }

    public function updateFormAction(string $path): void
    {
        $marketingUrl = $this->marketingUrlRepository->findByPath($path);

        if ($marketingUrl) {
            $this->view->assignMultiple([
                'marketingUrl' => $marketingUrl,
                'updateUri' => $this->getActionUri('update'),
                'documentOptions' => $this->findDocumentOptions(),
                'csrfProtectionToken' => $this->securityContext->getCsrfProtectionToken()
            ]);
        } else {
            $this->addFlashMessage(
                'Der Pfad "' . $path . '" ist zur Zeit nicht vergeben.',
                'Aktion gescheitert',
                Message::SEVERITY_ERROR
            );
            $this->redirect('index');
        }
    }

    /**
     * @phpstan-param array<string,string> $command
     */
    public function updateAction(array $command): void
    {
        $domainCommand = UpdateMarketingUrl::fromArray($command);
        try {
            $this->marketingUrlRepository->handleUpdateMarketingUrl($domainCommand);
            $this->addFlashMessage(
                'Marketing-URL "' . $domainCommand->getPath() . '" wurde aktualisiert.',
                'Aktion erfolgreich'
            );
        } catch (PathIsNotYetOccupied $exception) {
            $this->addFlashMessage(
                'Der Pfad "' . $domainCommand->getPath() . '" ist nicht vergeben.',
                'Aktion gescheitert',
                Message::SEVERITY_ERROR
            );
        }
        $this->redirect('index');
    }

    /**
     * @phpstan-param array<string,string> $command
     */
    public function deleteAction(array $command): void
    {
        $domainCommand = DeleteMarketingUrl::fromArray($command);
        try {
            $this->marketingUrlRepository->handleDeleteMarketingUrlUrl($domainCommand);
            $this->addFlashMessage(
                'Marketing-URL "' . $domainCommand->getPath() . '" wurde gelöscht.',
                'Aktion erfolgreich'
            );
        } catch (PathIsNotYetOccupied $exception) {
            $this->addFlashMessage(
                'Der Pfad "' . $domainCommand->getPath() . '" ist nicht vergeben.',
                'Aktion gescheitert',
                Message::SEVERITY_ERROR
            );
        }
        $this->redirect('index');
    }

    /**
     * @return array<string,array<int,array<string,string>>>
     */
    private function findDocumentOptions(): array
    {
        /** @var array<int,Node> $documents */
        /** @phpstan-ignore-next-line magic method */
        $documents = (new FlowQuery([$this->getSubgraph()->getCurrentSiteNode()]))
            ->find('[instanceof Neos.Neos:Document]')->get();
        usort(
            $documents,
            fn(NodeInterface $a, NodeInterface $b):int
            => strtolower($a->getLabel()) <=> strtolower($b->getLabel())
        );

        $documentOptions = [
            'Produkte' => [],
            'Seiten' => [],
            'Verweise' => []
        ];
        foreach ($documents as $document) {
            if ($document->isHidden()) {
                continue;
            }
            if ($document->getNodeType()->isOfType('Neos.Neos:Shortcut')) {
                $type = 'Verweise';
            } elseif ($document->getNodeType()->isOfType('Hoerbiger.HoerbigerCom.Catalog:Document.Product')) {
                $type = 'Produkte';
            } else {
                $type = 'Seiten';
            }

            $documentOptions[$type][] = [
                'identifier' => $document->getIdentifier(),
                'label' => $document->getLabel()
            ];
        }

        return $documentOptions;
    }

    private function getSubgraph(): ContentContext
    {
        /** @var ContentContext $subgraph */
        $subgraph = $this->contentContextFactory->create([
            'dimensions' => ['language' => ['en']],
            'targetDimensions' => ['language' => 'en'],
            'invisibleContentShown' => true,
            'inaccessibleContentShown' => true
        ]);

        return $subgraph;
    }

    /**
     * @phpstan-param array<mixed,mixed> $parameters
     */
    private function getActionUri(string $actionName, array $parameters = []): Uri
    {
        return new Uri($this->controllerContext
            ->getUriBuilder()
            ->setCreateAbsoluteUri(true)
            ->uriFor(
                $actionName,
                $parameters,
                'MarketingUrlManagement',
                'Hoerbiger.HoerbigerCom',
                'Application'
            ));
    }

    protected function initializeView(ViewInterface $view): void
    {
        parent::initializeView($view);
        /** @var \Neos\Fusion\View\FusionView $view */
        $view->setFusionPathPattern(
            'resource://Hoerbiger.HoerbigerCom/Private/Fusion/Integration/MarketingUrlManagement'
        );
        $view->assignMultiple([
            'flashMessages' => $this->controllerContext->getFlashMessageContainer()->getMessagesAndFlush()
        ]);
    }
}
