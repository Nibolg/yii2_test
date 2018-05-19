<?php

namespace app\models;

use yii\db\ActiveRecord;

class Order extends ActiveRecord
{

    private  static $statuses = [
        0 => 'Pending',
        1 => 'In progress',
        2 => 'Completed',
        3 => 'Canceled',
        4 => 'Failed'
    ];

    private static $modes = [
        0 => 'Manual',
        1 => 'Auto',
    ];
    
    private static $search_types = [
        1 => 'orders.id',
        2 => 'link',
        3 => 'user'
    ];

    /**
     * @return array
     */
    public static function getStatuses()
    {
        return self::$statuses;
    }

    /**
     * @return array
     */
    public static function getModes()
    {
        return self::$modes;
    }
    

    private static $pageSize = 100;

    /**
     * @return int
     */
    public static function getPageSize()
    {
        return self::$pageSize;
    }


    /**
     * @param int $pageSize
     */
    public static function setPageSize($pageSize)
    {
        self::$pageSize = $pageSize;
    }


    public static function tableName()
    {
        return '{{orders}}';
    }

    public function getStatusText()
    {
        return self::$statuses[(int)$this->status];
    }

    public function getModeText()
    {
        return self::$modes[(int)$this->mode];
    }

    public function getDateText()
    {
        return gmdate("Y-m-d", $this->created_at);
    }
    public function getTimeText()
    {
        return gmdate("H:i:s", $this->created_at);
    }

    public function getService()
    {
        return $this->hasOne(Service::class, ['id' => 'service_id']);
    }

    public static function search($params)
    {
        $query = self::find();
        $filter = [];
        if (isset($params['status']) && $params['status']!== '') {
            $filter['status'] = $params['status'];
        }
        if (isset($params['mode']) && $params['mode']!== '') {
            $filter['mode'] = $params['mode'];
        }
        if (!empty($params['search-type']) && isset($params['search'])) {
            $attr = self::$search_types[$params['search-type']];
            $filter[$attr] = $params['search'];
        }
        if (isset($params['service_id']) && $params['service_id']!== '') {
            $filter['service_id'] = $params['service_id'];
        }
        $query->joinWith('service');
        $query->filterWhere($filter);
        $query->orderBy("id desc");
        return $query;
    }

}