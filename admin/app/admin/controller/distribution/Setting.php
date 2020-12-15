<?php

namespace app\admin\controller\distribution;

use app\admin\controller\AuthController;
use app\admin\model\store\StoreDeliveryConfig;
use app\admin\model\system\SystemConfig;
use app\enum\DeliveryEnum;
use app\models\system\SystemStore;
use common\basic\BaseModel;
use common\services\JsonService;


/**
 * 管理员列表控制器
 * Class SystemAdmin
 * @package app\admin\controller\system
 */
class Setting extends AuthController
{

    /**
     * 默认的配送规则
     *
     * @return \think\Response
     */
    public function index()
    {
        $storeDeliveryConfigModel = new StoreDeliveryConfig();
        $id=SystemConfig::getConfigValue(DeliveryEnum::HEADER_STORE_KEY);
        $storeDeliveryConfig = $storeDeliveryConfigModel->with('store')
            ->where('store_id', $id)
            ->find();
        if ($this->request->isAjax()) {
            BaseModel::beginTrans();
            $post = $this->request->post();
            $post['store_id']=$post['headStoreId'];
            $post['weight_amount_list'] = json_encode($post['weight_amount_list']);
            $post['distance_amount_list'] = json_encode($post['distance_amount_list']);
            $post['update_time']=date('Y-m-d H:i:s');
            $headStoreId=$post['headStoreId'];
            unset($post['headStoreId']);
            //不存在则插入
            if (empty($storeDeliveryConfig)) {
                $post['create_time']=date('Y-m-d H:i:s');
                $res = $storeDeliveryConfigModel->insertGetId($post);
            } else {
                $res = $storeDeliveryConfigModel->where('store_id', $id)->update($post);
            }
            //设置 总店的id
            $headStoreRes=SystemConfig::where('menu_name',DeliveryEnum::HEADER_STORE_KEY)->update(['value'=>json_encode($headStoreId)]);
            //删除缓存的总店
            SystemStore::delHeadStore();
            BaseModel::checkTrans($res && $headStoreRes);
            //判断结果
            if ($res && $headStoreRes){
                return  JsonService::success('修改成功！');
            }else{
                return JsonService::fail('修改失败或者您没有修改什么！');
            }
        }
        if($storeDeliveryConfig==null){
            $storeDeliveryConfig=StoreDeliveryConfig::getDefaultConfig(0);
        }
        $storeDeliveryConfig['weight_amount_list'] = json_decode($storeDeliveryConfig['weight_amount_list'], true);
        $storeDeliveryConfig['distance_amount_list'] = json_decode($storeDeliveryConfig['distance_amount_list'], true);
        $storeDeliveryConfig['headStoreId']=$id;
        $this->assign(compact('storeDeliveryConfig'));
        return $this->fetch();
    }
}
