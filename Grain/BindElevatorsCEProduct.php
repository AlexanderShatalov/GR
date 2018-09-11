<?php
/**
 * Created by PhpStorm.
 * User: Shatalov980
 * Date: 24.08.2018
 * Time: 11:40
 */

namespace REB\Grain;


class BindElevatorsCEProduct
{
    private $elevator_code;

    private $product_code;

    public function __construct($elevator_code, $product_code)
    {
        $this->elevator_code = $elevator_code;
        $this->product_code = $product_code;
    }

    public function getFields() {
        return array(
            'ELEVATOR_CODE' => $this->elevator_code,
            'PRODUCT_CODE' => $this->product_code
        );


    }
}