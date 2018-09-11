<?php
/**
 * Created by PhpStorm.
 * User: rodionov133
 * Date: 16.08.2018
 * Time: 10:38
 */

namespace REB\Main\Database\Orm;

use \Bitrix\Main\Entity,
    \REB\Main\MdaDB;


class GrainTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'tbl_grain';
    }

    public static function getMap()
    {
        return array(
            new Entity\IntegerField('ID', array(
                'primary' => true,
                'autocomplete' => true
            )),
            new Entity\StringField('ASSET', array(
                'required' => true
            )),
            new Entity\DateField('DATE'),
            new Entity\FloatField('PRICE', array(
                'required' => true
            ))
        );
    }

    public static function deleteOldEntries(\DateTime $dateTime){
        $dt = $dateTime->format("Y-m-d");
        $conn = MdaDB::getInstance()->getConnect();
        $sql = 'delete from ' . static::getTableName() . ' where DATE < convert(?, DATE)';
        $del = $conn->prepare($sql);
        $del->execute([$dt]);
    }
}