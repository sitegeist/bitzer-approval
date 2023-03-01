<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Command;

/*
 * This script belongs to the "Hoerbiger.Shared" package.
 **/

use Hoerbiger\Shared\Domain\EditorialStaff;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Cli\CommandController;

/**
 * A command controller to manage editors
 */
final class EditorCommandController extends CommandController
{
    private EditorialStaff $editorialStaff;

    public function __construct(
        EditorialStaff $editorialStaff
    ) {
        parent::__construct();
        $this->editorialStaff = $editorialStaff;
    }

    public function promoteCommand(string $accountIdentifier): void
    {
        try {
            $this->editorialStaff->promoteEditor($accountIdentifier);
        } catch (\Exception $e) {
            $this->outputLine($e->getMessage());
        }
    }

    public function demoteCommand(string $accountIdentifier): void
    {
        try {
            $this->editorialStaff->demoteEditor($accountIdentifier);
        } catch (\Exception $e) {
            $this->outputLine($e->getMessage());
        }
    }
}
