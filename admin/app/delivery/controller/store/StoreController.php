<?php

namespace app\delivery\controller\store;

use app\admin\model\distribution\Deliveryman;
use app\delivery\controller\DeliveryBasic;
use app\delivery\model\system\SystemStore;
use app\Request;
use think\facade\App;

/**
 * 商品类
 * Class StoreProductController
 * @package app\api\controller\store
 */
class StoreController extends DeliveryBasic
{


    protected  $model;


    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model=new SystemStore();
    }

    /**
     * 门店列表
     * @param Request $request
     * @return mixed
     */
    public function getAll(Request $request)
    {
       $storeList=  $this->model->getAll();
        if ($storeList){
            return app('json')->successful('获取门店列表成功',['storeList'=>$storeList]);
        }else{
            return app('json')->fail('获取门店列表失败');
        }

    }


    /**
     * 门店列表
     * @param int $deliverymanId
     * @param int $isStoreActive
     * @param int $isWorkActive
     * @return mixed
     * @throws \Exception
     */
    public function getWorkStores(int $isStoreActive=0,int $isWorkActive=0)
    {
        $storeList=  $this->model->getWorkStores($this->deliveryMan->id, $isStoreActive,$isWorkActive);
        if ($storeList){
            return app('json')->successful('获取工作门店列表成功',['storeList'=>$storeList]);
        }else{
            return app('json')->fail('工作门店列表为空');
        }

    }

    public function storeDetail($storeId=0){
        $storeItem= $this->model->where('id',$storeId)->find();
        if ($storeItem){
            return app('json')->successful('获取门店成功',['store'=>$storeItem]);
        }else{
            return app('json')->fail('获取门店失败');
        }
    }



}