<?php declare(strict_types=1);

namespace Hoerbiger\Shared\Aspect;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Aop\JoinPointInterface;
use Neos\Flow\Validation\Validator\GenericObjectValidator;
use Neos\Flow\Validation\Validator\StringLengthValidator;
use Neos\Media\Domain\Model\Asset;
use Neos\Utility\ObjectAccess;

/**
 * The object validation aspect
 *
 * @Flow\Aspect
 */
class ObjectValidationAspect
{
    /**
     * @Flow\Before("method(Neos\Flow\Validation\Validator\GenericObjectValidator->isValid())")
     * @param JoinPointInterface $joinPoint
     */
    public function postprocessValidationResult(JoinPointInterface $joinPoint): void
    {
        if ($joinPoint->getMethodArgument('object') instanceof Asset) {
            $this->allowAssetTitlesOfArbitraryLength($joinPoint);
        }
    }

    private function allowAssetTitlesOfArbitraryLength(JoinPointInterface $joinPoint): void
    {
        /** @var GenericObjectValidator $validator */
        $validator = $joinPoint->getProxy();
        foreach ($validator->getPropertyValidators('title') as $propertyValidator) {
            if ($propertyValidator instanceof StringLengthValidator) {
                $options = ObjectAccess::getProperty($propertyValidator, 'options', true);
                $options['maximum'] = 90000;
                ObjectAccess::setProperty($propertyValidator, 'options', $options, true);
            }
        }
    }
}
