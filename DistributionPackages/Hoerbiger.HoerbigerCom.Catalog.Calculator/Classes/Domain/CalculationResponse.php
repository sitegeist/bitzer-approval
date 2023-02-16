<?php

namespace Hoerbiger\HoerbigerCom\Catalog\Calculator\Domain;

class CalculationResponse
{
    public $compressor;
    public $opex;
    public $enviornment;
    public $efficiency;
    public $reliability;
    public $percentageSavings;
    public $savings;
    public $percentageOpex;

    public function set_compressor($compressor)
    {
        $this->compressor = $compressor;
    }

    public function get_compressor()
    {
        return $this->compressor;
    }

    public function set_opex($opex)
    {
        $this->opex = $opex;
    }

    public function get_opex()
    {
        return $this->opex;
    }

    public function set_enviornment($enviornment)
    {
        $this->enviornment = $enviornment;
    }

    public function get_enviornment()
    {
        return $this->enviornment;
    }

    public function set_efficiency($efficiency)
    {
        $this->efficiency = $efficiency;
    }

    public function get_efficiency()
    {
        return $this->efficiency;
    }

    public function set_reliability($reliability)
    {
        $this->reliability = $reliability;
    }

    public function get_reliability()
    {
        return $this->reliability;
    }

    public function set_percentageSaving($percentageSavings)
    {
        $this->percentageSavings = $percentageSavings;
    }

    public function get_percentageSaving()
    {
        return $this->percentageSavings;
    }

    public function set_savings($savings)
    {
        $this->savings = $savings;
    }

    public function get_savings()
    {
        return $this->savings;
    }

    public function set_percentageOpex($percentageOpex)
    {
        $this->percentageOpex = $percentageOpex;
    }

    public function get_percentageOpex()
    {
        return $this->percentageOpex;
    }
}

?>
