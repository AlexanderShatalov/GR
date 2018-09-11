<?php
/**
 * Created by PhpStorm.
 * User: Shatalov980
 * Date: 23.08.2018
 * Time: 16:39
 */

namespace REB\Main\Database\Orm;

use \Bitrix\Main\Entity;

class CommodityExchangeProductTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'tbl_commodity_product';
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
            new Entity\StringField('ACTIVE', array(
                'required' => false
            )),
        );
    }
}
