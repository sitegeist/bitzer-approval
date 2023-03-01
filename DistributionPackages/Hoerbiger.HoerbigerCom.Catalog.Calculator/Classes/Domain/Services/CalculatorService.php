<?php

namespace Hoerbiger\HoerbigerCom\Catalog\Calculator\Domain\Services;

use Hoerbiger\HoerbigerCom\Catalog\Calculator\Domain\CalculationResponse;
use Hoerbiger\HoerbigerCom\Catalog\Calculator\Domain\Constants\BackgroundInfoConstants;
use Hoerbiger\HoerbigerCom\Catalog\Calculator\Domain\Constants\CMConstants;
use Hoerbiger\HoerbigerCom\Catalog\Calculator\Domain\Constants\PMConstants;

class CalculatorService
{

    public static function calculate($UnitBuilderRequests)
    {
        $units = array();
        $groups = array();

        foreach ( $UnitBuilderRequests as $object ) {
            $groups[ strtolower($object->get_unitTag()) ][] = $object;
        }

        $opexTotal = 0.0;

        foreach ( $groups as $object ) {
            $CalculationResponse = self::calculateForDifferentUnits($object);
            $opexTotal = $opexTotal + $CalculationResponse->get_opex();
            $units[] = $CalculationResponse;
        }

        foreach ( $units as $i_value ) {
            $i_value->set_percentageSaving(round(( $i_value->get_savings() / $opexTotal ) * 100, 0));
            $i_value->set_percentageOpex(round($i_value->get_opex() / $opexTotal * 100, 0));
        }

        return $units;
    }

// <------------------calculation--------------->

    public static function calculateForDifferentUnits($REQUEST)
    {
        $CalculationResponse = new CalculationResponse();
        $power = self::calculatePower($REQUEST);
        $PM = self::calculatePM($REQUEST);
        $CM = self::calculateCM($REQUEST);
        $env = self::calculateEnviornment($power);
        $LPO = 0.0;
        $LPOPM = 0.0;
        $LPOCM = 0.0;

        if ( count($REQUEST) > 1 ) {
            $LPO = self::calculateLPOForMultiUnit($REQUEST);
            $LPOPM = self::CalculateLPOPMForMulti($REQUEST);
            $LPOCM = self::CalculateLPOCMForMulti($REQUEST);
        } else {
            $LPO = self::calculateLPO($REQUEST);
            $LPOPM = self::CalculateLPOPM($REQUEST);
            $LPOCM = self::CalculateLPOCM($REQUEST);
        }

        $LPO = $LPO + $LPOPM + $LPOCM;
        $Opex = $power + $PM + $CM + $env + $LPO;
        $spillBack = self::calculateSpillback($REQUEST);
        $valve = self::calculateValve($REQUEST);
        $PMRel = self::calculatePMForReliabilityCalculations($REQUEST);
        $CMRel = self::calculateCMForReliabilityCalculations($REQUEST);
        $enviornment = round(self::enviornment($spillBack, $valve), 2);
        $Compressor = self::compressorName($REQUEST);

        $CalculationResponse->set_efficiency(round($spillBack + $valve, 2));
        $CalculationResponse->set_enviornment(round($enviornment, 2));
        $CalculationResponse->set_opex(round($Opex, 2));
        $CalculationResponse->set_reliability(round($PMRel + $CMRel, 2));
        $CalculationResponse->set_compressor($Compressor);
        $CalculationResponse->set_savings(round($spillBack + $valve + $PMRel + $CMRel + $LPOCM + $LPOPM + $enviornment + $LPO, 2));

        return $CalculationResponse;
    }

// <--------------Region Opex calculation -------------------------->

    public static function calculatePower($REQUEST)
    {
        $result = 0;

        foreach ( $REQUEST as $i_value ) {
            $result = $result + ( $i_value->get_driverPower() * $i_value->get_operatingHrs() * BackgroundInfoConstants::ENERGYPRICE );
        }

        return $result;
    }

    public static function calculatePM($REQUEST)
    {
        $result = 0;

        foreach ( $REQUEST as $i_value ) {
            $result = $result + ( ( $i_value->get_driverPower() * PMConstants::KOVERHAUL + PMConstants::DOVERHAUL ) * PMConstants::NRCYL * $i_value->get_operatingHrs() );
        }

        return $result;
    }

    public static function calculateCM($REQUEST)
    {
        $result = 0;

        foreach ( $REQUEST as $i_value ) {
            $result = $result + ( $i_value->get_driverPower() * CMConstants::KREPAIR + CMConstants::DREPAIR ) * $i_value->get_correctiveMaintenanceInterventions();
        }

        return $result;
    }

    public static function calculateEnviornment($Power)
    {
        $result = 0;
        return $result + ( $Power / BackgroundInfoConstants::ENERGYPRICE * BackgroundInfoConstants::ELECTRICITYEMISSIONSFACTOR * BackgroundInfoConstants::CO2CERTIFICATECOST / 1000 );
    }

    public static function calculateLPO($REQUEST)
    {
        $result = 0;
        foreach ( $REQUEST as $i_value ) {
            $result = $result + max(( ( $i_value->get_loadRequired() - 100 ) / 100 ) * $i_value->get_operatingHrs() * ( BackgroundInfoConstants::PRODOUTPUT / 24 ), 0);
        }

        return $result;
    }

    public static function calculateLPOForMultiUnit($REQUEST)
    {
        $result = 0.0;

        foreach ( $REQUEST as $i_value ) {
            $result = $result + max(( ( $i_value->get_loadRequired() - 100 ) / 100 ) * $i_value->get_operatingHrs() * ( BackgroundInfoConstants::PRODOUTPUT / 24 ), 0);
        }

        return $result;
    }

// <--------------End region - opex calculation --------------------->

// <-------------- Region efficiency calculation -------------------->

    public static function calculateSpillback($REQUEST)
    {
        $result = 0;

        foreach ( $REQUEST as $i_value ) {
            $flag = false;
            if ( $i_value->get_loadRequired() / 100 < 1 ) {
                $result = $result + ( $i_value->get_driverPower() * ( 1 - ( $i_value->get_loadRequired() / 100 ) ) * $i_value->get_operatingHrs() );
            } else {
                $flag = true;
                $result = $result + ( $i_value->get_driverPower() * 0 * $i_value->get_operatingHrs() );
            }
        }

        return !$flag ? $result * 0.85 * BackgroundInfoConstants::ENERGYPRICE : 0;
    }

    public static function calculateValve($REQUEST)
    {
        $result = 0;

        foreach ( $REQUEST as $i_value ) {
            $result = $result + $i_value->get_driverPower() * 0.85 * BackgroundInfoConstants::VALVEEFFICIENCYIMPROVEMENT * $i_value->get_operatingHrs() * BackgroundInfoConstants::ENERGYPRICE;
        }

        return $result;
    }
// <-------------- End - Region efficiency calculation --------------->

// <---------------Region - Reliability calcualtion ------------------>

    public static function calculatePMForReliabilityCalculations($REQUEST)
    {
        $result = 0;

        foreach ( $REQUEST as $i_value ) {
            $result = $result + max(( 1 - $i_value->get_plannedMaintenanceInterval() / BackgroundInfoConstants::PMINDBEST ) * ( ( $i_value->get_driverPower() * PMConstants::KOVERHAUL ) + PMConstants::DOVERHAUL ) * PMConstants::NRCYL * $i_value->get_operatingHrs(), 0);
        }

        return $result;
    }

    public static function calculateCMForReliabilityCalculations($REQUEST)
    {
        $result = 0;
        foreach ( $REQUEST as $i_value ) {
            if ( $i_value->get_correctiveMaintenanceInterventions() <= 0 ) {
                $result = $result + 0;
            } else {
                $result = $result + max(( 1 - BackgroundInfoConstants::CMINDBEST / $i_value->get_correctiveMaintenanceInterventions() ) * ( $i_value->get_driverPower() * CMConstants::KREPAIR + CMConstants::DREPAIR ) * $i_value->get_correctiveMaintenanceInterventions(), 0);
            }
        }

        return $result;
    }
// <---------------End - Region Reliability calcualtion -------------->

// <---------------Region - Enviornment calculation ------------------>
    public static function enviornment($spillBack, $valve)
    {
        return round(( $spillBack + $valve ) * BackgroundInfoConstants::CO2CERTIFICATECOST * ( BackgroundInfoConstants::ELECTRICITYEMISSIONSFACTOR / 1000 ), 2);
    }

// <---------------End - Region Enviornment calculation -------------->

    public static function compressorName($REQUEST)
    {
        $unitTag = $REQUEST[ 0 ]->get_unitTag() . " ";
        $subString = "";
        $tagIndicator = [];
        if ( count($REQUEST) > 1 ) {

            for ( $i = 0; $i < count($REQUEST); ++$i ) {
                $tagIndicator[] = $REQUEST[ $i ]->get_tagIndicator();
            }

            sort($tagIndicator);

            for ( $j = 0; $j < count($tagIndicator); ++$j ) {
                if ( $j == ( count($tagIndicator) - 1 ) ) {
                    $subString = $subString . $tagIndicator[ $j ];
                } else {
                    $subString = $subString . $tagIndicator[ $j ] . "/";
                }
            }

            return $unitTag . $subString;

        } else {
            return $unitTag . $REQUEST[ 0 ]->get_tagIndicator();
        }
    }

// <---------------Region - LPO calculation for PM and CM ------------->
    public static function CalculateLPOPM($REQUEST)
    {
        $result = 0;
        foreach ( $REQUEST as $i_value ) {
            if ( $i_value->get_plannedMaintenanceInterval() > 0 ) {
                $result = $result + max($i_value->get_operatingHrs() + ( ( 1 / $i_value->get_plannedMaintenanceInterval() ) - ( 1 / BackgroundInfoConstants::PMINDBEST ) ) * BackgroundInfoConstants::PMDURATIONHRS - BackgroundInfoConstants::MAXOPERATINGHRSPERYEAR, 0) * ( BackgroundInfoConstants::PRODOUTPUT / 24 );
            }
        }

        return $result;
    }

    public static function CalculateLPOCM($REQUEST)
    {
        $result = 0;
        foreach ( $REQUEST as $i_value ) {
            $result = $result + max($i_value->get_correctiveMaintenanceInterventions() - BackgroundInfoConstants::CMINDBEST, 0) * BackgroundInfoConstants::CMDURATION * ( BackgroundInfoConstants::PRODOUTPUT / 24 );
        }

        return $result;
    }

// <---------------End Region - LPO calculation for PM and CM ------------->

// <---------------Region - Multi unit LPO calculation for PM and CM ------------->

    public static function CalculateLPOPMForMulti($REQUEST)
    {
        $result = 0;
        foreach ( $REQUEST as $i_value ) {
            if ( $i_value->get_plannedMaintenanceInterval() > 0 ) {
                $result = $result + max($i_value->get_operatingHrs() + ( ( 1 / $i_value->get_plannedMaintenanceInterval() ) - ( 1 / BackgroundInfoConstants::PMINDBEST ) ) * BackgroundInfoConstants::PMDURATIONHRS, 0);
            }
        }
        $result = Max($result - count($REQUEST) * BackgroundInfoConstants::MAXOPERATINGHRSPERYEAR, 0);
        return $result * ( BackgroundInfoConstants::PRODOUTPUT / 24 );
    }

    public static function CalculateLPOCMForMulti($REQUEST)
    {
        $result1 = 0.0;
        $result2 = 0.0;
        foreach ( $REQUEST as $i_value ) {
            if ( $i_value->get_plannedMaintenanceInterval() > 0 ) {
                $result1 = $result1 + $i_value->get_operatingHrs() + ( 1 / $i_value->get_plannedMaintenanceInterval() ) * BackgroundInfoConstants::PMDURATIONHRS;
                $result2 = $result2 + ( 1 / ( $i_value->get_operatingHrs() + $i_value->get_plannedMaintenanceInterval() * BackgroundInfoConstants::PMDURATIONHRS ) * $i_value->get_correctiveMaintenanceInterventions() * BackgroundInfoConstants::CMDURATION );
            }
        }

        $result1 = Max($result1 - ( count($REQUEST) - 1 ) * BackgroundInfoConstants::MAXOPERATINGHRSPERYEAR, 0);
        $result1 = min($result1, BackgroundInfoConstants::MAXOPERATINGHRSPERYEAR);
        $result2 = $result2 * ( BackgroundInfoConstants::PRODOUTPUT / 24 );
        return $result1 * $result2;
    }
// <---------------End Region - Multi unit LPO calculation for PM and CM ------------->

}
