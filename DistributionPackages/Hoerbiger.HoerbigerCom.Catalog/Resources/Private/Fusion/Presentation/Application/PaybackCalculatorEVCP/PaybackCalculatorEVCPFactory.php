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
                    HeadlineColor::COLOR_EXCITE,
                    HeadlineType::TYPE_H2,
                    Editable::fromNodeProperty($paybackCalculatorECVPNode, 'headline')
                )
            : null,
            \json_encode([
                'inputFields' => [
                    'cost_dryNaturalGas' => $paybackCalculatorECVPNode->getProperty('input_cost_dryNaturalGas'),
                    'cost_NGL' => $paybackCalculatorECVPNode->getProperty('input_cost_NGL'),
                    'compressor_annualOperationHours' => $paybackCalculatorECVPNode->getProperty('input_compressor_annualOperationHours'),
                    'compressor_cylindersNumber' => $paybackCalculatorECVPNode->getProperty('input_compressor_cylindersNumber'),
                    'compressor_nominalFlowRate' => $paybackCalculatorECVPNode->getProperty('input_compressor_nominalFlowRate'),
                    'compressor_outputIncrease' => $paybackCalculatorECVPNode->getProperty('input_compressor_outputIncrease')
                ],
                'calculationFields' => [
                    'unit_btu' => $paybackCalculatorECVPNode->getProperty('const_unit_btu'),
                    'unit_mmbtu' => $paybackCalculatorECVPNode->getProperty('const_unit_mmbtu'),
                    'unit_scf' => $paybackCalculatorECVPNode->getProperty('const_unit_scf'),

                    'unit_avgHeatingValue4NG' => $paybackCalculatorECVPNode->getProperty('const_unit_avgHeatingValue4NG'),

                    'composition_dryNaturalGas' => $paybackCalculatorECVPNode->getProperty('const_composition_dryNaturalGas'),
                    'composition_NGL' => $paybackCalculatorECVPNode->getProperty('const_composition_NGL'),
                    'composition_others' => $paybackCalculatorECVPNode->getProperty('const_composition_others'),

                    'cost_actuators' => $paybackCalculatorECVPNode->getProperty('const_cost_actuators'),
                    'cost_localPanel' => $paybackCalculatorECVPNode->getProperty('const_cost_localPanel'),
                    'cost_installation' => $paybackCalculatorECVPNode->getProperty('const_cost_installation'),

                    'cost_generalSubtotal' => $paybackCalculatorECVPNode->getProperty('const_cost_generalSubtotal'),
                    'cost_scheduleAndControl' => $paybackCalculatorECVPNode->getProperty('const_cost_scheduleAndControl'),
                    'cost_customerIntegration' => $paybackCalculatorECVPNode->getProperty('const_cost_customerIntegration'),
                    'cost_commission' => $paybackCalculatorECVPNode->getProperty('const_cost_commission'),
                ],
                'labels' => $this->csvPOTranslator->getLabels(self::class)
            ], JSON_THROW_ON_ERROR)
        );
    }
}
