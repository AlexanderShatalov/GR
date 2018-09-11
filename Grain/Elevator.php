<?php
/**
 * Created by PhpStorm.
 * User: Shatalov980
 * Date: 23.08.2018
 * Time: 15:21
 */

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