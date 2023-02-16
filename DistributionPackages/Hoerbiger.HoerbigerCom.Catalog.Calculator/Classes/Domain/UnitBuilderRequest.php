<?php

namespace Hoerbiger\HoerbigerCom\Catalog\Calculator\Domain;

class UnitBuilderRequest
{
    public $UnitTag;
    public $TagIndicator;
    public $DriverPower;
    public $LoadRequired;
    public $CorrectiveMaintenanceInterventions;
    public $PlannedMaintenanceInterval;
    public $OperatingHrs;

    public function set_unitTag($UnitTag)
    {
        $this->UnitTag = $UnitTag;
    }

    public function get_unitTag()
    {
        return $this->UnitTag;
    }

    public function set_tagIndicator($TagIndicator)
    {
        $this->TagIndicator = $TagIndicator;
    }

    public function get_tagIndicator()
    {
        return $this->TagIndicator;
    }

    public function set_driverPower($DriverPower)
    {
        $this->DriverPower = $DriverPower;
    }

    public function get_driverPower()
    {
        return $this->DriverPower;
    }

    public function set_loadRequired($LoadRequired)
    {
        $this->LoadRequired = $LoadRequired;
    }

    public function get_loadRequired()
    {
        return $this->LoadRequired;
    }

    public function set_correctiveMaintenanceInterventions($CorrectiveMaintenanceInterventions)
    {
        $this->CorrectiveMaintenanceInterventions = $CorrectiveMaintenanceInterventions;
    }

    public function get_correctiveMaintenanceInterventions()
    {
        return $this->CorrectiveMaintenanceInterventions;
    }

    public function set_plannedMaintenanceInterval($PlannedMaintenanceInterval)
    {
        $this->PlannedMaintenanceInterval = $PlannedMaintenanceInterval;
    }

    public function get_plannedMaintenanceInterval()
    {
        return $this->PlannedMaintenanceInterval;
    }

    public function set_operatingHrs($OperatingHrs)
    {
        $this->OperatingHrs = $OperatingHrs;
    }

    public function get_operatingHrs()
    {
        return $this->OperatingHrs;
    }
}
?>
