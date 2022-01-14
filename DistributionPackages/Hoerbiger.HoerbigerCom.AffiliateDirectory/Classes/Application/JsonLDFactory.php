<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\AffiliateDirectory\Application;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.AffiliateDirectory package.
 */

use Hoerbiger\HoerbigerCom\AffiliateDirectory\Domain\AffiliateRepository;
use Hoerbiger\HoerbigerCom\AffiliateDirectory\Presentation\Application\AffiliateFinder\Affiliate;
use Neos\Eel\ProtectedContextAwareInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Neos\Domain\Service\ContentContext;

final class JsonLDFactory implements ProtectedContextAwareInterface
{
    /**
     * @Flow\Inject
     * @var AffiliateRepository
     */
    protected $affiliateRepository;

    public function getCorporation(ContentContext $subGraph): string
    {
        return json_encode(
            [
                '@context' => 'https://schema.org',
                '@type' => 'Corporation',
                'name' => 'HOERBIGER Holding AG',
                'address' => [
                    '@type' => 'PostalAddress',
                    'addressCountry' => 'CH',
                    'addressLocality' => 'Zug',
                    'streetAddress' => 'Baarerstrasse 18',
                    'postalCode' => '6302'
                ],
                'email' => 'info@hoerbiger.com',
                'faxNumber' => '+41 (0) 41 560 10 11',
                'legalName' => 'HOERBIGER Holding AG',
                'telephone' => '+41 (0) 41 560 10 00',
                'url' => 'https://www.hoerbiger.com',
                'subOrganization' => array_map(function (Affiliate $affiliate): array {
                    return [
                        '@type' => 'LocalBusiness',
                        'address' => [
                            '@type' => 'PostalAddress',
                            'addressCountry' => $affiliate->getAddressCountry(),
                            'addressLocality' => $affiliate->getAddressLocality(),
                            'streetAddress' => $affiliate->getStreetAddress(),
                            'postalCode' => $affiliate->getPostalCode()
                        ],
                        'email' => $affiliate->getEmail(),
                        'legalName' => $affiliate->getLegalName(),
                        'name' => $affiliate->getLegalName(),
                        'telephone' => $affiliate->getTelephone()
                    ];
                }, $this->affiliateRepository->findAffiliates($subGraph->getDimensions()['language'][0])->toArray())
            ]
        )?: '{}';
    }

    public function allowsCallOfMethod($methodName): bool
    {
        return true;
    }
}
