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
 * 图文管理 Model
 * Class WechatNews
 * @package app\admin\model\wechat
 */
class DeliverymanApplywork extends BaseModel
{

    use ModelTrait;

    protected $pk = 'id';

    protected $name = 'store_deliveryman_applywork';


    public function store(){
        return  $this->hasOne("app\models\system\SystemStore",'id','store_id');
    }


    public function deliveryman(){
        return  $this->hasOne("app\models\delivery\Deliveryman",'id','deliveryman_id');
    }




}