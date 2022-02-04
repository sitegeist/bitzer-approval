<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\ContactFormLink;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Infrastructure\CsvPOTranslator;
use Hoerbiger\Shared\Presentation\Block\Button\Button;
use Hoerbiger\Shared\Presentation\Block\Button\ButtonType;
use Hoerbiger\Shared\Presentation\Block\Button\ButtonVariant;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineColor;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineSize;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineType;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineVariant;
use Hoerbiger\Shared\Presentation\Block\Tag\Tag;
use Hoerbiger\Shared\Presentation\Block\Text\Text;
use Hoerbiger\Shared\Presentation\Block\Text\TextVariant;
use Hoerbiger\Shared\Presentation\Layout\Stack\Stack;
use Hoerbiger\Shared\Presentation\Layout\Stack\StackVariant;
use Neos\ContentRepository\Domain\NodeAggregate\NodeName;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;

final class ContactFormLinkFactory extends AbstractComponentPresentationObjectFactory
{
    private CsvPOTranslator $csvPOTranslator;

    public function __construct(
        CsvPOTranslator $csvPOTranslator
    ) {
        $this->csvPOTranslator = $csvPOTranslator;
    }

    public function forSiteFooter(TraversableNodeInterface $site): ContactFormLinkInterface
    {
        $identity = function(SlotInterface $slot) {
            return $slot;
        };

        return new ContactFormLink(
            new Stack(
                StackVariant::xs(),
                Collection::fromIterable([
                    new Stack(
                        StackVariant::inline(),
                        Collection::fromIterable([
                            new Tag(
                                'strong',
                                null,
                                Value::fromString($this->csvPOTranslator->getLabel(ContactFormLink::class, 'contact_form_link_subline'))
                            ),
                            new Headline(
                                HeadlineSize::large(),
                                HeadlineColor::brand(),
                                HeadlineType::h2(),
                                Value::fromString($this->csvPOTranslator->getLabel(ContactFormLink::class, 'contact_form_link_headline'))
                            )
                        ], $identity),
                    ),
                    new Text(
                        TextVariant::neutralContrast(),
                        Value::fromString($this->csvPOTranslator->getLabel(ContactFormLink::class, 'contact_form_link_text')),
                        false
                    )
                ], $identity)
            ),
            new Button(
                ButtonType::link(),
                ButtonVariant::cta(),
                Value::fromString($this->csvPOTranslator->getLabel(ContactFormLink::class, 'contact_form_link_button')),
                $this->uriService->getNodeUri($site->findNamedChildNode(NodeName::fromString('contact'))),
                "_self",
                null
            )
        );
    }

    public function forDocument(TraversableNodeInterface $document, TraversableNodeInterface $site): ?ContactFormLinkInterface
    {
        switch ($document->getNodeTypeName()) {
            case 'Hoerbiger.HoerbigerCom:Document.HomePage':
            case 'Hoerbiger.HoerbigerCom:Document.WebPage':
            case 'Hoerbiger.HoerbigerCom:Document.NotFoundPage':
            case 'Hoerbiger.HoerbigerCom.ApplicationWorlds:Document.ApplicationWorld':
            case 'Hoerbiger.HoerbigerCom.Catalog:Document.Catalog':
            case 'Hoerbiger.HoerbigerCom.Catalog:Document.ProductCategory':
            case 'Hoerbiger.HoerbigerCom.Catalog:Document.Product':
            case 'Hoerbiger.HoerbigerCom.Catalog:Document.IndustrySolutions':
            case 'Hoerbiger.HoerbigerCom.Catalog:Document.IndustrySolution':
                return $this->forSiteFooter($site);
            case 'Hoerbiger.HoerbigerCom:Document.ContactPage':
            default:
                return null;
        }
    }
}
