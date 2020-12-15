<?php
/**
 *
 * @author: xaboy<365615158@qq.com>
 * @day: 2017/12/25
 */

namespace app\models\user;

use common\basic\BaseModel;
use common\traits\ModelTrait;

/**
 * TODO 用户收货地址标签
 * Class UserAddressTag
 * @package app\models\user
 */
class UserAddressTag extends BaseModel
{
    /**
     * 数据表主键
     * @var string
     */
    protected $pk = 'id';

    /**
     * 模型名称
     * @var string
     */
    protected $name = 'user_address_tag';

    use ModelTrait;

    /**
     * 设置默认收货地址
     * @param $id 地址id
     * @param $uid 用户uid
     * @return bool
     */
    public static function setDefaultAddress($id,$uid)
    {
        self::beginTrans();
        $res1 = self::where('uid',$uid)->update(['is_default'=>0]);
        $res2 = self::where('id',$id)->where('uid',$uid)->update(['is_default'=>1]);
        $res =$res1 !== false && $res2 !== false;
        self::checkTrans($res);
        return $res;
    }



}