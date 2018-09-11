<?php
/**
 * Created by PhpStorm.
 * User: Shatalov980
 * Date: 22.08.2018
 * Time: 16:29
 */

namespace REB\Main\Database\Orm;

use \Bitrix\Main\Entity;


class GrainElevatorsTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'tbl_grain_elevators';
    }
    public static function getMap()
    {
        return array(
            new Entity\IntegerField('ID', array(
                'primary' => true,
                'autocomplete' => true
            )),
            new Entity\StringField('CODE', array(
                'required' => true
            )),
            new Entity\StringField('NAME', array(
                'required' => true
            )),
            new Entity\StringField('REGION', array(
                'required' => true
            ))
        );
    }
}
