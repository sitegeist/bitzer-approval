<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Catalog\Presentation\Application\PaybackCalculatorEVCP;

/*
 * This file is part of the Hoerbiger.HoerbigerCom.Catalog package.
 */

use Hoerbiger\Shared\Infrastructure\CsvPOTranslator;
use Hoerbiger\Shared\Presentation\Block\Headline\Headline;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineColor;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineSize;
use Hoerbiger\Shared\Presentation\Block\Headline\HeadlineType;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Editable;

final class PaybackCalculatorEVCPFactory extends AbstractComponentPresentationObjectFactory
{
    public function __construct(
        private readonly CsvPOTranslator $csvPOTranslator
    ){
    }

    public function create(TraversableNodeInterface $paybackCalculatorECVPNode, bool $inBackend): PaybackCalculatorEVCP
    {
        return new PaybackCalculatorEVCP(
            $paybackCalculatorECVPNode->getProperty('headline') || $inBackend
                ? new Headline(
                    HeadlineSize::SIZE_MEDIUM_ITALIC,
                    HeadlineColor::COLOR_NEUTRAL_CONTRAST,
                    HeadlineType::TYPE_H2,
                    Editable::fromNodeProperty($paybackCalculatorECVPNode, 'headline')
                )
            : null,
            \json_encode([
                'form' => ['const_unit_scf' => $paybackCalculatorECVPNode->getProperty('const_unit_scf'),
                    'const_unit_avgHeatingValue4NG' => $paybackCalculatorECVPNode->getProperty('const_unit_avgHeatingValue4NG'),
                    'const_composition_dryNaturalGas' => $paybackCalculatorECVPNode->getProperty('const_composition_dryNaturalGas'),
                    'const_composition_NGL' => $paybackCalculatorECVPNode->getProperty('const_composition_NGL'),
                    'const_composition_others' => $paybackCalculatorECVPNode->getProperty('const_composition_others'),
                    'const_cost_actuators' => $paybackCalculatorECVPNode->getProperty('const_cost_actuators'),
                    'const_cost_localPanel' => $paybackCalculatorECVPNode->getProperty('const_cost_localPanel'),
                    'const_cost_installation' => $paybackCalculatorECVPNode->getProperty('const_cost_installation'),
                    'const_cost_generalSubtotal' => $paybackCalculatorECVPNode->getProperty('const_cost_generalSubtotal'),
                    'const_cost_scheduleAndControl' => $paybackCalculatorECVPNode->getProperty('const_cost_scheduleAndControl'),
                    'const_cost_customerIntegration' => $paybackCalculatorECVPNode->getProperty('const_cost_customerIntegration'),
                    'const_cost_commission' => $paybackCalculatorECVPNode->getProperty('const_cost_commission'),
                    'input_cost_dryNaturalGas' => $paybackCalculatorECVPNode->getProperty('input_cost_dryNaturalGas'),
                    'input_cost_NGL' => $paybackCalculatorECVPNode->getProperty('input_cost_NGL'),
                    'input_compressor_annualOperationHours' => $paybackCalculatorECVPNode->getProperty('input_compressor_annualOperationHours'),
                    'input_compressor_cylindersNumber' => $paybackCalculatorECVPNode->getProperty('input_compressor_cylindersNumber'),
                    'input_compressor_nominalFlowRate' => $paybackCalculatorECVPNode->getProperty('input_compressor_nominalFlowRate'),
                    'input_compressor_outputIncrease' => $paybackCalculatorECVPNode->getProperty('input_compressor_outputIncrease')
                ],
                'labels' => $this->csvPOTranslator->getLabels(self::class)
            ], JSON_THROW_ON_ERROR)
        );
    }
}
