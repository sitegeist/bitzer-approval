<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\SuccessStories\Presentation\Application\ArticleOverview;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.SuccessStories package.
 */

use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Flow\ResourceManagement\ResourceManager;
use Neos\Media\Domain\Model\Thumbnail;
use Neos\Media\Domain\Service\ThumbnailService;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use Sitegeist\CsvPO\Domain\TranslationLabel;
use Sitegeist\CsvPO\Domain\TranslationLabelSourceRepository;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\I18n\Service as LocalizationService;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;
use Hoerbiger\Shared\Domain\FormattedDateFactory;
use Neos\ContentRepository\Domain\Model\Node;

/**
 * @Flow\Scope("singleton")
 */
final class ArticleOverviewFactory extends AbstractComponentPresentationObjectFactory
{
    private TranslationLabelSourceRepository $translationLabelSourceRepository;

    private LocalizationService $localizationService;

    private ThumbnailService $thumbnailService;

    private ResourceManager $resourceManager;

    private FormattedDateFactory $formattedDateFactory;

    public function __construct(
        TranslationLabelSourceRepository $translationLabelSourceRepository,
        LocalizationService $localizationService,
        ThumbnailService $thumbnailService,
        ResourceManager $resourceManager,
        FormattedDateFactory $formattedDateFactory
    ) {
        $this->translationLabelSourceRepository = $translationLabelSourceRepository;
        $this->localizationService = $localizationService;
        $this->thumbnailService = $thumbnailService;
        $this->resourceManager = $resourceManager;
        $this->formattedDateFactory = $formattedDateFactory;
    }

    public function forMagazine(TraversableNodeInterface $magazine): ArticleOverview
    {
        return new ArticleOverview(
            json_encode([
                'labels' => $this->getLabels() ?: [],
                'articles' => $this->getArticleData($magazine) ?: []
            ]) ?: ''
        );
    }

    /**
     * @return array<string,string>
     */
    private function getLabels(): array
    {
        $labelSource = $this->translationLabelSourceRepository->findOneByIdentifier(
            'resource://Hoerbiger.HoerbigerCom.SuccessStories/Private/Fusion/Presentation/Application/ArticleOverview/ArticleOverview.translation.csv'
        );
        return array_map(function (TranslationLabel $label): string {
            return (string)$label->findTranslationForLocaleChain($this->localizationService->getLocaleChain(
                $this->localizationService->getConfiguration()->getCurrentLocale()
            )) ?: $label->getIdentifier();
        }, $labelSource ? $labelSource->findAllTranslationLabels() : []);
    }

    /**
     * @return array<int,mixed>
     */
    private function getArticleData(TraversableNodeInterface $magazine): array
    {
        return array_map(
            function(TraversableNodeInterface $successStory): array {
                $image = $successStory->getProperty('preview__image') ?: $successStory->getProperty('image');

                try {
                    $rendition = $image
                        ? $this->thumbnailService->getThumbnail(
                            $image,
                            $this->thumbnailService->getThumbnailConfigurationForPreset('md')
                        )
                        : null;
                } catch (\Exception $exception) {
                    $rendition = null;
                }

                /** @var Node $successStory */
                $datePublished = $successStory->getProperty('datePublished') ?: $successStory->getCreationDateTime();
                /** @var TraversableNodeInterface $successStory */

                return [
                    //'datePublished' => (string)Value::fromString($this->formattedDateFactory->createFullDate($datePublished)),
                    'image' => [
                        'src' => $rendition instanceof Thumbnail ? $this->resourceManager->getPublicPersistentResourceUri($rendition->getResource()) : '',
                        'alt' => $successStory->getProperty('preview__image__alt') ?: $successStory->getProperty('image__alt') ?: '',
                        'title' => $successStory->getProperty('preview__image__title') ?: $successStory->getProperty('image__title') ?: ''
                    ],
                    'headline' => $successStory->getProperty('preview__headline') ?: $successStory->getProperty('headline') ?: '',
                    'abstract' => $successStory->getProperty('preview__description') ?: $successStory->getProperty('abstract') ?: '',
                    'applicationWorld' => $successStory->getProperty('applicationWorld')
                        ? $successStory->getProperty('applicationWorld')->getLabel()
                        : '',
                    'url' => (string)$this->uriService->getNodeUri($successStory)
                ];
            },
            $this->findChildNodesByNodeTypeFilterString(
                $magazine,
                'Hoerbiger.HoerbigerCom.SuccessStories:Document.SuccessStory'
            )->toArray()
        );
    }
}
