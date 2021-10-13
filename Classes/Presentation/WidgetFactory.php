<?php declare(strict_types=1);
namespace Sitegeist\Bitzer\Approval\Presentation;

use GuzzleHttp\Psr7\Uri;
use Neos\Eel\ProtectedContextAwareInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\I18n\Translator;
use Neos\Fusion\FusionObjects\AbstractFusionObject;
use Sitegeist\Bitzer\Presentation\Widget;
use Sitegeist\Bitzer\Presentation\WidgetInterface;

/**
 * The widget implementation
 * @Flow\Scope("singleton")
 */
final class WidgetFactory implements ProtectedContextAwareInterface
{
    private Translator $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function forApprovalAssignments(AbstractFusionObject $component): WidgetInterface
    {
        $uriBuilder = clone $component->getRuntime()->getControllerContext()->getUriBuilder();

        return new Widget(
            'fas fa-user-check',
            new Uri($uriBuilder->uriFor(
                'index',
                [],
                'ApprovalAssignment',
                'Sitegeist.Bitzer.Approval',
                'Application'
            )),
            $this->getLabel('approvalAssignments.label'),
            $this->getLabel('approvalAssignments.description'),
            null
        );
    }

    private function getLabel(string $id, array $arguments = []): string
    {
        return $this->translator->translateById(
            $id,
            $arguments,
            null,
            null,
            'Module.Bitzer.Approval',
            'Sitegeist.Bitzer.Approval'
        ) ?: $id;
    }

    public function allowsCallOfMethod($methodName): bool
    {
        return true;
    }
}
