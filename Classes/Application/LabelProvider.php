<?php declare(strict_types=1);
namespace Sitegeist\Bitzer\Approval\Application;

use Neos\Eel\ProtectedContextAwareInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\I18n\Translator;

/**
 * @Flow\Scope("singleton")
 */
final class LabelProvider implements ProtectedContextAwareInterface
{
    private Translator $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @return array<string,string>
     */
    public function forEditApprovalTaskForm(): array
    {
        return [
            'actions.back.label' => $this->getLabel('actions.back.label')
        ];
    }

    /**
     * @return array<string,string>
     */
    public function forPrepareApprovalTaskForm(): array
    {
        return [
            'scheduleTask.label' => $this->getLabel('scheduleTask.label'),
            'actions.cancel.label' => $this->getLabel('actions.cancel.label')
        ];
    }

    private function getLabel(string $id, array $arguments = []): string
    {
        return $this->translator->translateById(
            $id,
            $arguments,
            null,
            null,
            'Module.Bitzer',
            'Sitegeist.Bitzer'
        );
    }

    /**
     * @param string $methodName
     */
    public function allowsCallOfMethod($methodName): bool
    {
        return true;
    }
}
