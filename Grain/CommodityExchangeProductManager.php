<?php
/**
 * Created by PhpStorm.
 * User: Shatalov980
 * Date: 23.08.2018
 * Time: 16:53
 */

namespace REB\Grain;

use \REB\Main\Database\Orm\CommodityExchangeProductTable;

class CommodityExchangeProductManager
{

    const CODE = 0;

    const NAME = 1;

    const ACTIVE = 2;

    private $dataFile = false;

    public function readCSVFile($path, $length = 0, $delimiter = ',', $enclosure = '"', $escape = '\\') {

        if (($handle = fopen($path, "r")) !== false) {
            $arDATA = array();
            while(($data = fgetcsv($handle, $length, $delimiter)) !== false){
                $arDATA[] = $data;
            }
            fclose($handle);

        }else{
            Throw new \Exception('Ошибка');
        }
        $this->dataFile = $arDATA;
        return $this;
    }

    public function add(CommodityExchangeProduct $product) {
        $fields = $product->getFields();
        try{
            $result = CommodityExchangeProductTable::add($fields);
            if ($result->isSuccess()){
                return $result->getId();
            }
        }catch(\Exception $e){
            debmes($e, 'adding elem', 'red');
        }
        return false;

    }


    public function insertData() {
        $data = $this->dataFile;
        $arProducts = array();

        foreach ($data as $datum) {
            $products = new CommodityExchangeProduct($datum[self::CODE], $datum[self::NAME], $datum[self::ACTIVE]);
            $arProducts[] = $this->add($products);
        }
        return $arProducts;
    }


}