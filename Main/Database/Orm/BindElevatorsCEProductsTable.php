<?php


namespace REB\Main\Database\Orm;

use \Bitrix\Main\Entity;

class BindElevatorsCEProductsTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'tbl_bind_elevators_ceproducts';
    }
    public static function getMap()
    {
        return array(
            new Entity\StringField('ELEVATOR_CODE', array(
                'primary' => true
            )),
            new Entity\ReferenceField(
                'ELEVATOR',
                'REB\Main\Database\Orm\GrainElevatorsTable',
                array('=this.ELEVATOR_CODE' => 'ref.CODE')
            ),
            new Entity\StringField('PRODUCT_CODE', array(
                'primary' => true
            )),
            new Entity\ReferenceField(
                'PRODUCT',
                'REB\Main\Database\Orm\CommodityExchangeProductTable',
                array('=this.PRODUCT_CODE' => 'ref.CODE')
            )
        );
    }
}
