<?php


namespace REB\Grain;


class CommodityExchangeProduct
{

    private $code;

    private $name;

    private $active;

    public function __construct($code, $name, $active)
    {
        $this->code = $code;
        $this->name = $name;
        $this->active = $active;
    }

    public function getFields() {
        return array(
            'CODE' => $this->code,
            'NAME' => $this->name,
            'ACTIVE' => $this->active
        );


    }


}
