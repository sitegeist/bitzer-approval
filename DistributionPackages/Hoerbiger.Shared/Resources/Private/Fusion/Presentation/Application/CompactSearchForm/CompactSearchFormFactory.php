<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Application\CompactSearchForm;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use Neos\ContentRepository\Domain\NodeAggregate\NodeName;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\I18n\Service as LocalizationService;
use Sitegeist\CsvPO\Domain\TranslationLabel;
use Sitegeist\CsvPO\Domain\TranslationLabelSourceRepository;
use Hoerbiger\Shared\Presentation\Block\Icon\Icon;
use Hoerbiger\Shared\Presentation\Block\Icon\IconSize;

final class CompactSearchFormFactory extends AbstractComponentPresentationObjectFactory
{
    /**
     * @var TranslationLabelSourceRepository
     * @Flow\Inject
     */
    protected $translationLabelSourceRepository;

    /**
     * @Flow\Inject
     * @var LocalizationService
     */
    protected $localizationService;

    public function forDocument(TraversableNodeInterface $site): CompactSearchForm
    {
        $labelSource = $this->translationLabelSourceRepository->findOneByIdentifier(
            'resource://Hoerbiger.Shared/Private/Fusion/Presentation/Application/CompactSearchForm/CompactSearchForm.translation.csv'
        );
        $labels = array_map(function (TranslationLabel $label): string {
            return $label->findTranslationForLocaleChain($this->localizationService->getLocaleChain(
                $this->localizationService->getConfiguration()->getCurrentLocale()
            ))->getTranslation() ?: $label->getIdentifier();
        }, $labelSource ? $labelSource->findAllTranslationLabels() : []);

        return new CompactSearchForm(
            $this->uriService->getNodeUri($site->findNamedChildNode(NodeName::fromString('searchresults')), true),
            new Icon("shared", "search", IconSize::SIZE_SIZE_1_5),
            json_encode($labels) ?: '{}'
        );
    }
}
