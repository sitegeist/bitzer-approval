<?php

namespace Hoerbiger\HoerbigerCom\Catalog\Calculator\Domain\Services;

use Hoerbiger\HoerbigerCom\Catalog\Calculator\Domain\UnitBuilderRequest;

class UtilityService
{

    public static function createMap($data)
    {

        $unitBuilderReqObjCollection = array();
        foreach ( $data as $object ) {
            $unitBuilderReqObj = new UnitBuilderRequest();
            $unitBuilderReqObj->set_unitTag($object->unitTag);
            $unitBuilderReqObj->set_tagIndicator($object->tagIndicator);
            $unitBuilderReqObj->set_driverPower($object->driverPower);
            $unitBuilderReqObj->set_loadRequired($object->loadRequired);
            $unitBuilderReqObj->set_correctiveMaintenanceInterventions($object->correctiveMaintenanceInterventions);
            $unitBuilderReqObj->set_plannedMaintenanceInterval($object->plannedMaintenanceInterval);
            $unitBuilderReqObj->set_operatingHrs($object->operatingHrs);
            $unitBuilderReqObjCollection[] = $unitBuilderReqObj;
        }

        return $unitBuilderReqObjCollection;
    }

    public static function deliveryResponse($status, $statusMessage, $data)
    {
        $response[ 'Status' ] = $status;
        $response[ 'StatusMessage' ] = $statusMessage;
        $response[ 'data' ] = $data;
        return json_encode($response);
    }
}

?>
