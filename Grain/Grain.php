<?php


namespace REB\Grain;

class Grain
{
    private $asset;

    private $date;

    private $price;

    public function __construct($asset, $date, $price){
        $this->asset = $asset;
        $this->date = $date;
        $this->price = $price;
    }

    public function getFields(){
        return array(
            'ASSET' => $this->asset,
            'DATE' => $this->date,
            'PRICE' => $this->price
        );
    }

    public function getAssetArray(){
        $ass = explode('_', $this->asset);
        return array(
            'asset' => $ass[0],
            'product_code' => $ass[1],
            'basis' => $ass[2]
        );
    }

    public function getPrice(){
        return $this->price;
    }

    public function getDate(){
        return $this->date;
    }
}
