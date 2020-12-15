<?php

/**
 *
 * @author: xaboy<365615158@qq.com>
 * @day: 2017/11/02
 */

namespace app\models\delivery;

use app\admin\model\store\StoreProduct;
use app\admin\model\system\SystemAdmin;
use app\models\OrderDelivery;
use app\models\system\SystemStore;
use app\models\user\User;
use common\traits\ModelTrait;
use common\basic\BaseModel;
use think\facade\Db;

/**
 * 配送员 和门店的 工作关联 Model
 * Class WechatNews
 * @package app\admin\model\wechat
 */
class DeliverymanWork extends BaseModel
{
    use ModelTrait;


    protected $autoWriteTimestamp = 'datetime';

    protected $pk = 'id';

    protected $name = 'store_deliveryman_work';

    public static  $allowFields=['store_id','deliveryman_id','create_time','update_time','status','mark'];


    public function store(){
        return  $this->hasOne("app\models\system\SystemStore",'id','store_id');
    }


    public function deliveryman(){
        return  $this->hasOne("app\models\delivery\Deliveryman",'id','deliveryman_id');
    }




}