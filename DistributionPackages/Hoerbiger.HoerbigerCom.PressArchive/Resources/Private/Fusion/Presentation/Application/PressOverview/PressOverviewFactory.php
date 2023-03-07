<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\PressArchive\Presentation\Application\PressOverview;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.PressArchive package.
 */

use GuzzleHttp\Psr7\Uri;
use Hoerbiger\Shared\Domain\FormattedDateFactory;
use Hoerbiger\Shared\Infrastructure\CsvPOTranslator;
use Hoerbiger\Shared\Presentation\Block\Button\Button;
use Hoerbiger\Shared\Presentation\Block\Button\ButtonType;
use Hoerbiger\Shared\Presentation\Block\Button\ButtonVariant;
use Hoerbiger\Shared\Presentation\Block\Link\Link;
use Hoerbiger\Shared\Presentation\Block\Link\LinkTarget;
use Hoerbiger\Shared\Presentation\Block\Link\LinkVariant;
use Neos\ContentRepository\Domain\NodeAggregate\NodeName;
use Neos\Flow\Annotations as Flow;
use Hoerbiger\Shared\Presentation\Block\Card\PressReleaseCard\PressReleaseCard;
use Hoerbiger\Shared\Presentation\Block\Card\PressReleaseCard\PressReleaseCardVariant;
use Hoerbiger\Shared\Presentation\Block\Figure\FigureFactory;
use Hoerbiger\Shared\Presentation\Block\Figure\ImageSourceFactory;
use Neos\ContentRepository\Domain\Model\Node;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;

final class PressOverviewFactory extends AbstractComponentPresentationObjectFactory
{
    /**
     * @Flow\Inject
     * @var ImageSourceFactory
     */
    protected $imageSourceFactory;

    /**
     * @Flow\Inject
     * @var FigureFactory
     */
    protected $figureFactory;

    /**
     * @Flow\Inject
     * @var CsvPOTranslator
     */
    protected $csvPOTranslator;

    /**
     * @Flow\Inject
     * @var FormattedDateFactory
     */
    protected $formattedDateFactory;

    public function forOverviewPage(
        TraversableNodeInterface $overviewPage,
        bool $inBackend
    ): SlotInterface
    {
        $pressReleases = [];

        $labels = $this->csvPOTranslator->getLabels(PressOverview::class);
        foreach ( $overviewPage->findChildNodes() as $magazine) {
            $pressReleases = array_merge(
                $pressReleases,
                $this->findChildNodesByNodeTypeFilterString(
                    $magazine,
                    'Hoerbiger.HoerbigerCom:Document'
                )->toArray()
            );
        };

        uasort($pressReleases, function (Node $a, Node $b) {
            return ($b->getProperty('datePublished') ?: $b->getCreationDateTime())
                <=> ($a->getProperty('datePublished') ?: $a->getCreationDateTime());
        });

        $pressReleaseCards = array_map(
            function (Node $pressRelease) use ($inBackend, $labels): Link
            {
                /** @var PressReleaseCardVariant $pressVariant */
                $pressVariant = PressVariant::from('news');

                switch ((string)$pressRelease->getNodeType()) {
                    case 'Hoerbiger.HoerbigerCom.PressArchive:Document.HannsRelease':
                        $pressVariant = PressVariant::from('hanns');
                        break;
                    case 'Hoerbiger.HoerbigerCom.PressArchive:Document.YearbookRelease':
                        $pressVariant = PressVariant::from('yearbook');
                        break;
                    case 'Hoerbiger.HoerbigerCom.PressArchive:Document.PressRelease':
                        $pressVariant = PressVariant::from('news');
                        break;
                    default:
                        throw PressVariantIsInvalid::becauseItMustBeOneOfTheDefinedConstants((string)$pressRelease->getNodeType());
                }

                $imageSource = $this->imageSourceFactory->forPreviewMixin($pressRelease, $inBackend);

                $figure = $imageSource && !$pressVariant->getIsNews()
                    ? $this->figureFactory->forPressReleaseCard($imageSource)
                    : null;


                $datePublished = $this->formattedDateFactory->createFullDate(
                    $pressRelease->getProperty('datePublished') ?: $pressRelease->getCreationDateTime()
                );
                return new Link(
                    LinkVariant::container(),
                    $this->uriService->getNodeUri($pressRelease),
                    LinkTarget::self(),
                    null,
                    new PressReleaseCard(
                        PressReleaseCardVariant::from($pressVariant->getValue()),
                        $pressVariant->getIsNews()
                            ? Value::fromString($datePublished ?: '')
                            : null,
                        $figure,
                        Value::fromString(
                            $pressRelease->getProperty('preview__headline')
                                ?: $pressRelease->getProperty('headline')
                                ?: ''
                        ),
                        Value::fromString(
                            $labels['label.filter.' . $pressVariant->getValue()]
                            . $pressRelease->getProperty('edition') ?: ''
                        ),
                        Value::fromString(
                            $pressRelease->getProperty('preview__description')
                                ?: $pressRelease->getProperty('abstract')
                                ?: ''
                        ),
                        new Button(
                            ButtonType::regular(),
                            ButtonVariant::cta(),
                            Value::fromString($this->getLabel('list.learnMore')),
                            null,
                            null,
                            null
                        )
                    ),
                    null
                );
            },
            $pressReleases
        );



        $hannsMagazine = $overviewPage->findNamedChildNode(NodeName::fromString('hanns'));

        $hannsMetaCardImageSource = $this->imageSourceFactory->forPreviewMixin(
            $hannsMagazine,
            $inBackend
        );

        $hansMetaCardFigure = $hannsMetaCardImageSource
            ? $this->figureFactory->forPressReleaseCard($hannsMetaCardImageSource)
            : null;

        $hansMetaCard = new Link(
            LinkVariant::container(),
            new Uri('#'),
            LinkTarget::self(),
            null,
            new PressReleaseCard(
                PressReleaseCardVariant::meta(),
                null,
                $hansMetaCardFigure,
                Value::fromString($hannsMagazine->getProperty('preview__headline') ?: ''),
                null,
                Value::fromString($hannsMagazine->getProperty('preview__description') ?: ''),
                new Button(
                    ButtonType::regular(),
                    ButtonVariant::cta(),
                    Value::fromString($labels['label.button.getNewestRelease']),
                    null,
                    null,
                    null
                )
            ),
            null
        );

        array_splice($pressReleaseCards, 2, 0 , '');
        $pressReleaseCards[2] = $hansMetaCard;
        return new PressOverview(
            $labels,
            Collection::fromIterable(
                $pressReleaseCards
            )
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
