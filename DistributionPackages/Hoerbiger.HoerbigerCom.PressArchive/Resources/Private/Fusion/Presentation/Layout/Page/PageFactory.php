<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\PressArchive\Presentation\Layout\Page;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.PressArchive package.
 */

use Hoerbiger\Shared\Domain\FormattedDateFactory;
use Hoerbiger\Shared\Presentation\Block\Button\Button;
use Hoerbiger\Shared\Presentation\Block\Button\ButtonType;
use Hoerbiger\Shared\Presentation\Block\Button\ButtonVariant;
use Hoerbiger\Shared\Presentation\Block\Card\PressReleaseCard\PressReleaseCard;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineType;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineVariant;
use Hoerbiger\Shared\Presentation\Block\Link\LinkTarget;
use Hoerbiger\Shared\Presentation\Block\SiteHeader\SiteHeader;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainer;
use Hoerbiger\Shared\Presentation\Layout\ContentContainer\ContentContainerVariant;
use Hoerbiger\Shared\Presentation\Layout\Grid\Grid;
use Hoerbiger\Shared\Presentation\Layout\Grid\GridVariant;
use Hoerbiger\Shared\Presentation\Layout\Page\Page;
use Hoerbiger\Shared\Presentation\Layout\Page\PageInterface;
use Hoerbiger\Shared\Integration\CacheSegment\CacheSegment;
use Hoerbiger\Shared\Presentation\Block\SiteFooter\SiteFooterFactory;
use Hoerbiger\Shared\Presentation\Layout\Stage\StageFactory;
use Neos\ContentRepository\Domain\Model\Node;
use Neos\ContentRepository\Domain\NodeAggregate\NodeName;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Content;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;

/**
 * @Flow\Scope("singleton")
 */
final class PageFactory extends AbstractComponentPresentationObjectFactory
{
    private StageFactory $stageFactory;

    private FormattedDateFactory $formattedDateFactory;

    public function __construct(StageFactory $stageFactory, FormattedDateFactory $formattedDateFactory)
    {
        $this->stageFactory = $stageFactory;
        $this->formattedDateFactory = $formattedDateFactory;
    }

    public function forPressArchive(TraversableNodeInterface $site, TraversableNodeInterface $magazine, bool $inBackend): PageInterface
    {
        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forFullMediaIntroduction($magazine, $inBackend);

        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        $pressReleases = $magazine->findChildNodes()->toArray();
        uasort($pressReleases, function (Node $a, Node $b) {
            return ($b->getProperty('datePublished') ?: $b->getCreationDateTime())
                <=> ($a->getProperty('datePublished') ?: $a->getCreationDateTime());
        });

        $pressReleasesByYear = [];
        foreach ($pressReleases as $pressRelease) {
            /** @var \DateTimeInterface $datePublished */
            /** @var Node $pressRelease */
            $datePublished = $pressRelease->getProperty('datePublished') ?: $pressRelease->getCreationDateTime();
            $pressReleasesByYear[$datePublished->format('Y')][] = $pressRelease;
        }
        $listContent = [];
        foreach ($pressReleasesByYear as $year => $pressReleasesForYear) {
            $gridContent = [
                new Headline(HeadlineVariant::regular(), HeadlineType::h2(), Value::fromString((string)$year)),
                new Grid(
                    GridVariant::oneColumn(),
                    Collection::fromIterable(
                        $pressReleasesForYear,
                        function (TraversableNodeInterface $pressRelease): PressReleaseCard {
                            /** @var Node $pressRelease */
                            $datePublished = $pressRelease->getProperty('datePublished') ?: $pressRelease->getCreationDateTime();
                            return new PressReleaseCard(
                                null,
                                Value::fromString(
                                    $pressRelease->getProperty('preview__headline')
                                        ?: $pressRelease->getProperty('headline')
                                        ?: ''
                                ),
                                Value::fromString($this->formattedDateFactory->createFullDate($datePublished)),
                                Value::fromString(
                                    $pressRelease->getProperty('preview__description')
                                        ?: $pressRelease->getProperty('abstract')
                                        ?: ''
                                ),
                                new Button(
                                    ButtonType::link(),
                                    ButtonVariant::cta(),
                                    Value::fromString($this->getLabel('list.learnMore')),
                                    $this->uriService->getNodeUri($pressRelease),
                                    LinkTarget::TARGET_SELF,
                                    null
                                )
                            );
                        }
                    )
                )
            ];

            $listContent[] = new Grid(
                GridVariant::oneColumn(),
                Collection::fromIterable($gridContent, $identity)
            );
        }

        return new Page(
            new SiteHeader(),
            Collection::fromIterable(array_filter([
                new ContentContainer(
                    ContentContainerVariant::regularSm(),
                    Collection::fromIterable([
                        $stage,
                        new CacheSegment(
                            new Grid(
                                GridVariant::oneColumn(),
                                Collection::fromIterable($listContent, $identity)
                            ),
                            'Hoerbiger.HoerbigerCom.PressArchive:CacheSegment.PressReleaseList'
                        )
                    ], $identity)
                ),
            ]), $identity),
            (new SiteFooterFactory())->forDocument($magazine, $site)
        );
    }

    public function forPressRelease(TraversableNodeInterface $site, TraversableNodeInterface $pressRelease, bool $inBackend): PageInterface
    {
        /** @var SlotInterface $stage */
        $stage = $this->stageFactory->forFullMediaIntroduction($pressRelease, $inBackend);

        return $this->forDocumentWithIntroduction($site, $pressRelease, $stage);
    }

    public function forDocumentWithIntroduction(TraversableNodeInterface $site, TraversableNodeInterface $document, SlotInterface $introSection): PageInterface
    {
        $identity = function (SlotInterface $slot) {
            return $slot;
        };

        return new Page(
            new SiteHeader(),
            Collection::fromIterable(array_filter([
                new ContentContainer(
                    ContentContainerVariant::regularSm(),
                    $introSection
                ),
                Content::fromNode($document->findNamedChildNode(NodeName::fromString('main')))
            ]), $identity),
            (new SiteFooterFactory())->forDocument($document, $site)
        );
    }

    /**
     * @param array<mixed,mixed> $arguments
     */
    private function getLabel(string $identifier, array $arguments = []): string
    {
        return $this->translator->translateById(
            $identifier,
            $arguments,
            null,
            null,
            'PressReleases',
            'Hoerbiger.HoerbigerCom.PressArchive'
        ) ?: $identifier;
    }
}
