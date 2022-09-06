<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Layout\Stage\VideoStage;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use GuzzleHttp\Psr7\Uri;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureInterface;
use Hoerbiger\Shared\Presentation\Layout\Stage\StageInterface;

interface VideoStageInterface extends StageInterface
{
    public function getDesktopVideoUri(): Uri;

    public function getMobileFigure(): FigureInterface;
}
