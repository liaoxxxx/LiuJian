<?php
namespace app\api\controller\store;

use app\admin\model\system\SystemGroupDataProduct;
use app\Request;

/**
 * 首页活动商品控制器
 * Class Discovery
 * @package app\api\controller\store
 */
class ActivityProductController
{

    /**
     * 首页活动商品
     * @param $id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index($id)
    {
        $data['group_data_id'] = $id;
        return app('json')->successful(SystemGroupDataProduct::getGroupDataProducts($data));
    }

}
