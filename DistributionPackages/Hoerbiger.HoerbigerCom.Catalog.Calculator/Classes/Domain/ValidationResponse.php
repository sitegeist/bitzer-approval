<?php

namespace Hoerbiger\HoerbigerCom\Catalog\Calculator\Domain;

class ValidationResponse{
    public $IsValid;
    public $Response;

    public function set_isValid($IsValid)
    {
        $this->IsValid = $IsValid;
    }

    public function get_isValid(){
        return $this->IsValid;
    }

    public function set_response($Response)
    {
        $this->Response = $Response;
    }

    public function get_response(){
        return $this->Response;
    }
}

?>
