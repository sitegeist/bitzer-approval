<?php

namespace Hoerbiger\HoerbigerCom\Catalog\Calculator\Domain;

class ValidationError{
    public $UnitTag;
    public $IsValid;
    public $Error;
    function set_isValid($IsValid)
    {
        $this->IsValid = $IsValid;
    }
    public function set_error($Error)
    {
        $this->Error = $Error;
    }
    public function set_unitTag($UnitTag)
    {
        $this->UnitTag = $UnitTag;
    }
}
?>
