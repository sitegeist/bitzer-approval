<?php declare(strict_types=1);
namespace Hoerbiger\Shared\Presentation\Block\ContactFormLink;

/*
 * This file is part of the Hoerbiger.Shared package.
 */

use Hoerbiger\Shared\Presentation\Block\Button\Button;
use Hoerbiger\Shared\Presentation\Block\Button\ButtonType;
use Hoerbiger\Shared\Presentation\Block\Button\ButtonVariant;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineType;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineVariant;
use Hoerbiger\Shared\Presentation\Block\Tag\Tag;
use Hoerbiger\Shared\Presentation\Block\Text\Text;
use Hoerbiger\Shared\Presentation\Block\Text\TextVariant;
use Hoerbiger\Shared\Presentation\Block\Translation\TranslationFactory;
use Hoerbiger\Shared\Presentation\Layout\Stack\Stack;
use Hoerbiger\Shared\Presentation\Layout\Stack\StackVariant;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;

final class ContactFormLinkFactory extends AbstractComponentPresentationObjectFactory
{
    public function forSiteFooter(TraversableNodeInterface $site): ContactFormLinkInterface
    {
        $identity = function(SlotInterface $slot) {
            return $slot;
        };

        $translationFile = 'resource://Hoerbiger.Shared/Private/Fusion/Presentation/Block/ContactFormLink/ContactFormLink.translation.csv';
        $translate = new TranslationFactory();

        return new ContactFormLink(
            new Stack(
                StackVariant::xs(),
                Collection::fromIterable([
                    new Stack(
                        StackVariant::inline(),
                        Collection::fromIterable([
                            new Tag(
                                'strong',
                                $translate('contact_form_link_subline', $translationFile)
                            ),
                            new Headline(
                                HeadlineVariant::regular(),
                                HeadlineType::h2(),
                                $translate('contact_form_link_headline', $translationFile)
                            )
                        ], $identity),
                    ),
                    new Text(
                        TextVariant::neutralContrast(),
                        $translate('contact_form_link_text',  $translationFile),
                        false
                    )
                ], $identity)
            ),
            new Button(
                ButtonType::link(),
                ButtonVariant::cta(),
                Value::fromString($this->getLabelFor('ContactFormLinkButton')),
                $this->uriService->getNodeUri($site), // TODO => Traverse Document.ContactPage.
                "_self",
                null
            )
        );
    }

    private function getLabelFor(string $identifier): string
    {
        switch ($identifier) {
            case 'ContactFormLinkSubline':
                return 'KONTAKT';
            case 'ContactFormLinkHeadline':
                return 'Haben Sie noch Fragen?';
            case 'ContactFormLinkText':
                return 'Falls Sie noch Fragen oder Anmerkungen zu unseren Produkten haben, füllen Sie gerne unser Kontakformular aus und wir werden uns schnellstmöglich mit Ihnen in Verbindung setzten.';
            case 'ContactFormLinkButton':
                return 'Zum Kontaktformular';
            default:
                return '';
        }
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
                return $this->forSiteFooter($site);
            case 'Hoerbiger.HoerbigerCom:Document.ContactPage':
            default:
                return null;
        }
    }
}
