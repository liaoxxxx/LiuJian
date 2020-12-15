<?php
namespace app\api\controller\store;

use app\admin\model\discovery\Discovery;

/**
 * 发现页商品控制器
 * Class Discovery
 * @package app\api\controller\store
 */
class DiscoveryProductController
{

    /**
     * 发现商品
     * @return mixed
     */
    public function index()
    {
        return app('json')->successful(Discovery::getDiscoveryProductList());
    }

}
