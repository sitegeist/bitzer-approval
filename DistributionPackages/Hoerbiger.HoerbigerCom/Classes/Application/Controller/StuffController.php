<?php declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Application\Controller;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use Hoerbiger\HoerbigerCom\Domain\Stuff\Stuff;
use Hoerbiger\HoerbigerCom\Domain\Stuff\StuffQuery;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Controller\RestController;

/**
 * @Flow\Scope("singleton")
 */
class StuffController extends RestController
{
    private Stuff $stuff;

    /**
     * @var array<int,string>
     */
    protected $supportedMediaTypes = ['application/json'];

    /**
     * @var array<string,string>
     */
    protected $viewFormatToObjectNameMap = ['json' => 'Neos\Flow\Mvc\View\JsonView'];

    public function __construct(Stuff $stuff)
    {
        $this->stuff = $stuff;
    }

    public function queryAction(string $query): void
    {
        $things = $this->stuff->findThingsByQuery(StuffQuery::fromJsonString($query));

        $this->view->assign('value', $things);
    }
}
