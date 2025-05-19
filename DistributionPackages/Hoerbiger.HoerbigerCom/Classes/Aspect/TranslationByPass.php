<?php

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Aspect;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Aop\JoinPointInterface;

/**
 * @Flow\Aspect
 * @Flow\Scope("singleton")
 */
final class TranslationByPass
{
    #[Flow\InjectConfiguration(path: 'translation.bypassEnabled')]
    protected bool $isBypassEnabled;

    /**
     * @return array<string,string>
     */
    #[Flow\Around("method(Sitegeist\LostInTranslation\Infrastructure\DeepL\DeepLTranslationService->translate())")]
    public function returnDummyTranslations(JoinPointInterface $joinPoint): array
    {
        /** @var array<string,string> $texts */
        $texts = $joinPoint->getMethodArgument('texts');

        $translatedTexts = [];

        if ($this->isBypassEnabled) {
            foreach ($texts as $key => $text) {
                $translatedTexts[$key] = $text . '-translated';
            }
            return $translatedTexts;
        }

        return $joinPoint->getAdviceChain()->proceed($joinPoint);
    }
}
