<?php


namespace app\delivery\model\system;

use app\models\store\StoreDeliveryman;
use common\traits\ModelTrait;
use common\basic\BaseModel;
use think\Collection;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/**
 * 门店自提 model
 * Class SystemStore
 * @package app\admin\model\system
 */
class OrderDelivery extends BaseModel
{
    use ModelTrait;

    /**
     * 数据表主键
     * @var string
     */
    protected $pk = 'id';

    /**
     * 模型名称
     * @var string
     */
    protected $name = 'store_order_delivery';


    public static function getLatlngAttr($value, $data)
    {
        return $data['latitude'] . ',' . $data['longitude'];
    }



    /**
     * 获取所有的门店
     * @param array $where
     * @return array
     */
    public static function getAll($where = array())
    {
        $model = new self;
        $model = $model->order('id desc');
        return self::page($model, function ($item) {
            $item['store_name'] = $item->profile->store_name ?? '';
        }, $where);
    }




}