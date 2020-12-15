<?php


namespace app\models\invoice;

use app\models\store\StoreOrder;
use common\services\SystemConfigService;
use think\facade\Session;
use common\traits\ModelTrait;
use common\basic\BaseModel;
use common\traits\JwtAuthModelTrait;

/**
 * TODO 用户Model
 * Class User
 * @package app\models\user
 */
class Invoice extends BaseModel
{
    use JwtAuthModelTrait;
    use ModelTrait;

    protected $pk = 'id';

    protected $name = 'invoice';

    protected function setAddTimeAttr($value)
    {
        return time();
    }

    /**
     * 用户发票
     * @param $uid
     * @param $page
     * @param $limit
     * @param $type
     * @return array|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function userInvoice($uid, $page, $limit, $type=0)
    {
        if($page){
            $list = self::where('uid',$uid)
                ->where('type',$type)
                ->order('add_time DESC')
                ->page((int)$page,(int)$limit)
                ->select();
        }else{
            $list = self::where('uid',$uid)
                ->where('type',$type)
                ->order('add_time DESC')
                ->select();
        }
        $list = count($list) ? $list->toArray() : [];
        foreach ($list as &$v){
            $v['add_time'] = date('Y-m-d H:i:s',$v['add_time']);
        }
        return $list;
    }

}