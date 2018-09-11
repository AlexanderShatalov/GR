<?php
/**
 * Created by PhpStorm.
 * User: Shatalov980
 * Date: 24.08.2018
 * Time: 11:34
 */

namespace REB\Grain;

use \REB\Main\Database\Orm\BindElevatorsCEProductsTable;

class BindElevatorsCEProductManager
{
    const ELEVATOR_CODE = 0;

    const PRODUCT_CODE = 1;

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

    public function add(BindElevatorsCEProduct $bindElevatorsCEProduct) {
        $fields = $bindElevatorsCEProduct->getFields();
        try{
            $result = BindElevatorsCEProductsTable::add($fields);
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
        $arBindElevatorsCEProduct = array();

        foreach ($data as $datum) {
            $bindElevatorsCEProduct = new BindElevatorsCEProduct($datum[self::ELEVATOR_CODE], $datum[self::PRODUCT_CODE]);
            $arBindElevatorsCEProduct[] = $this->add($bindElevatorsCEProduct);
        }
        return $arBindElevatorsCEProduct;
    }
}