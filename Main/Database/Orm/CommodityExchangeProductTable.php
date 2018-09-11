<?php


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
