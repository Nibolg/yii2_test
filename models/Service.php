<?php

namespace app\models;

use yii\db\ActiveRecord;
use \yii\db\Query;

class Service extends ActiveRecord
{
    public static function tableName()
    {
        return '{{services}}';
    }

    public static function getStat()  {
        $query = new Query();
        $query->select(['count(*) as orders_count', 'services.*' ]);
        $query->from('services');
        $query->leftJoin('orders', 'orders.service_id = services.id');
        $query->groupBy('id');
        return $query->all();
    }
}