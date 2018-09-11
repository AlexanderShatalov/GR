<?php

namespace REB\Grain;


class Elevator
{
    private $code;

    private $name;

    private $region;

    public function __construct($code, $name, $region)
    {
        $this->code = $code;
        $this->name = $name;
        $this->region = $region;
    }

    public function getFields() {
        return array(
            'CODE' => $this->code,
            'NAME' => $this->name,
            'REGION' => $this->region
        );


    }
}
