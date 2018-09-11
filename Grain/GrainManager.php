<?php

namespace REB\Grain;

use \Bitrix\Main\Type,
    \REB\Main\Database\Orm\GrainTable,
    \REB\Main\CSVManager;

class GrainManager{

    const ASSET = 0;

    const DATE = 1;

    const PRICE = 2;

    const FILE_DATE_FORMAT = 'd.m.Y';

    private $grainGenerator = false;

    public function __construct(){
    }

    public function add(Grain $grain){
        $fields = $grain->getFields();
        try{
            $result = GrainTable::add($fields);
            if ($result->isSuccess()){
                return $result->getId();
            }
        }catch(\Exception $e){
            debmes($e, 'adding elem', 'red');
        }
        return false;
    }

    public function parse($dateFilter){
        if(!$this->grainGenerator){
            Throw new \Exception('Отсутсвуют данные! Необходимо прочесть файл');
        }
        while($line = $this->grainGenerator->current()){
            if($line[self::DATE] == $dateFilter){
                yield $line;
            }
            $this->grainGenerator->next();
        }
    }

    public function readFile($path, $is_relative = false){
        $GrainGenerator = CSVManager::CSVtoGenerator($path, $is_relative);
        if(!$GrainGenerator || !$GrainGenerator->current()){
            Throw new \Exception('Cannot read file');
        }
        $GrainGenerator->next();
        $this->grainGenerator = $GrainGenerator;
        return $this;
    }

    public function insertData($data){
        $ids = array();
        foreach($data as $el){
            $date = new Type\Date($el[self::DATE], self::FILE_DATE_FORMAT);
            $grain = new Grain($el[self::ASSET], $date, $el[self::PRICE]);
            $ids[] = $this->add($grain);
        }
        return $ids;
    }

    public function deleteOldEntries($newdate){
        $dateTime = \DateTime::createFromFormat(self::FILE_DATE_FORMAT, $newdate);
        GrainTable::deleteOldEntries($dateTime);
    }

    public function getAssetByProductCode($code){
        try{
            $res = GrainTable::getList(array(
                'select' => array('ASSET', 'PRICE', 'DATE'),
                'filter' => array('=ASSET' => $code),
                'order' => array('DATE' => 'DESC'),
                'limit' => 1,
                //'cache' => 60             //  У нас битрикс 16.0.11, а параметр доступен с 16.5.9
            ));
        }catch(\Bitrix\Main\ArgumentException $ae){
            return false;
        }
        $row = $res->fetchAll()[0];
        if($row['DATE'] instanceof \Bitrix\Main\Type\Date){
            //  Сраное ORM и тут подкинуло головной боли
            $row['DATE'] = $row['DATE']->format(GrainManager::FILE_DATE_FORMAT);
        }
        $grain = new Grain($row['ASSET'], $row['PRICE'], $row['DATE']);
        return $grain;
    }
}
