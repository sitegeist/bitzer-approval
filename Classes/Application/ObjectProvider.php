<?php declare(strict_types=1);
namespace Sitegeist\Bitzer\Approval\Application;

use Neos\Eel\ProtectedContextAwareInterface;
use Neos\Flow\Annotations as Flow;
use Sitegeist\Bitzer\Application\Object\LabeledObjectAddresses;
use Sitegeist\Bitzer\Approval\Domain\Approval\ObjectRepository;

/**
 * @Flow\Scope("singleton")
 */
final class ObjectProvider implements ProtectedContextAwareInterface
{
    private ObjectRepository $objectRepository;

    public function __construct(ObjectRepository $objectRepository)
    {
        $this->objectRepository = $objectRepository;
    }

    public function getObjectsToBeApproved(): LabeledObjectAddresses
    {
        return $this->objectRepository->findToBeApproved();
    }

    public function allowsCallOfMethod($methodName): bool
    {
        return true;
    }
}
