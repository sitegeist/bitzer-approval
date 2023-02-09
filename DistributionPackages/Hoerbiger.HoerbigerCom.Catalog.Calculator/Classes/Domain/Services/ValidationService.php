<?php

namespace Hoerbiger\HoerbigerCom\Catalog\Calculator\Domain\Services;


use Hoerbiger\HoerbigerCom\Catalog\Calculator\Domain\ValidationError;
use Hoerbiger\HoerbigerCom\Catalog\Calculator\Domain\ValidationResponse;

class ValidationService
{

    public static function validateRequest($request)
    {
        $Error = array();
        $IsValidRequest = true;
        foreach ( $request as $object ) {

            $unitTag = trim($object->unitTag);
            $loadRequired = $object->loadRequired;
            $tagIndicator = trim($object->tagIndicator);
            $driverPower = $object->driverPower;
            $correctiveMaintenanceInterventions = $object->correctiveMaintenanceInterventions;
            $plannedMaintenanceInterval = $object->plannedMaintenanceInterval;
            $operatingHrs = $object->operatingHrs;

            if ( $unitTag == "" || strlen($unitTag) > 20 || $loadRequired == "" || $loadRequired < 0 || $loadRequired > 150
                || strlen($tagIndicator) > 20 || $driverPower == "" || $driverPower < 0 || $driverPower > 20000
                || $correctiveMaintenanceInterventions == "" || $correctiveMaintenanceInterventions < 0 || $correctiveMaintenanceInterventions > 10
                || $plannedMaintenanceInterval == "" || $plannedMaintenanceInterval < 0 || $plannedMaintenanceInterval > 10
                || $operatingHrs == "" || $operatingHrs < 0 || $operatingHrs > 8760 ) {
                $validationError = new ValidationError();
                $validationError->set_error("One or more validations failed.");
                $validationError->set_unitTag($object->unitTag);
                $validationError->set_isValid(false);
                $IsValidRequest = false;
                $Error[] = $validationError;
            }
        }

        if ( $IsValidRequest ) {
            $Response = new ValidationResponse();
            $Response->set_isValid($IsValidRequest);
            return $Response;
        } else {
            $Response = new ValidationResponse();
            $Response->set_isValid($IsValidRequest);
            $Response->set_response($Error);
            return $Response;
        }
    }
}

?>
