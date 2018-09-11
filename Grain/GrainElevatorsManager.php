<?php

namespace REB\Grain;

use \REB\Main\Database\Orm\GrainElevatorsTable;


class GrainElevatorsManager
{

    const CODE = 0;

    const NAME = 1;

    const REGION = 2;

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

    public function add(Elevator $elevators) {
        $fields = $elevators->getFields();
        try{
            $result = GrainElevatorsTable::add($fields);
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
        $arElevators = array();

        foreach ($data as $datum) {
            $elevators = new Elevator($datum[self::CODE], $datum[self::NAME], $datum[self::REGION]);
            $arElevators[] = $this->add($elevators);
        }
        return $arElevators;
    }
}
