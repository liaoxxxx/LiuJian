<?php

namespace app\admin\controller\system;

use app\admin\controller\AuthController;
use app\admin\model\store\StoreDeliveryConfig;
use app\admin\model\system\SystemConfig;
use app\models\delivery\DeliverymanApplywork;
use app\models\delivery\DeliverymanWork;
use common\services\JsonService;
use common\services\FormBuilder as Form;
use common\services\JsonService as Json;
use common\services\UtilService as Util;
use Exception;
use think\facade\App;
use think\facade\Route as Url;
use app\admin\model\system\SystemStore as SystemStoreModel;
use common\services\UtilService;
use think\facade\Session;

/**
 * 门店管理控制器
 * Class SystemAttachment
 * @package app\admin\controller\system
 *
 */
class SystemStore extends AuthController
{


    private $model;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new SystemStoreModel();
    }


    /**
     * 门店设置
     *
     */
    public function list()
    {
        $where = Util::getMore([
            ['page', $this->request->param('page', 1)],
            ['limit', $this->request->param('limit', 10)],
            ['is_show', $this->request->param('is_show', null)],
            ['status', $this->request->param('status', null)],
            ['add_time', $this->request->param('add_time', null)],
            ['storeName', $this->request->param('storeName', null)],
        ], $this->request);
        $this->assign(compact($where));
        return $this->fetch();
    }


    public function store_list()
    {

        $where = Util::getMore([
            ['page', $this->request->param('page', 1)],
            ['limit', $this->request->param('limit', 10)],
            ['is_show', $this->request->param('is_show', null)],
            ['status', $this->request->param('status', null)],
            ['add_time', $this->request->param('add_time', null)],
            ['storeName', $this->request->param('storeName', null)],
            ['group_by', $this->request->param('group_by', null)],
        ], $this->request);
        //dump($where['is_show']);die;
        $storeList = null;
        $storeModel = $this->model;
        if (isset($where['status']) && $where['status'] != '') {
            $storeModel = $storeModel->where('status', $where['status']);
        }
        if (isset($where['is_show']) && $where['is_show'] != '') {
            $storeModel = $storeModel->where('is_show', $where['is_show']);
        }
        if ($where['add_time']) {
            $addTimeArray = explode(' - ', $where['add_time']);
            $start = $addTimeArray[0] . ' 00:00:00';
            $end = $addTimeArray[0] . ' 23:59:59';
            $storeModel = $storeModel->whereTime('add_time', 'between', [$start, $end]);
        }

        if ($where['storeName']) {
            $storeModel = $storeModel->where('name', 'like', '%' . $where['storeName'] . '%');
        }
        $storeList = $storeModel->where(['is_del' => 0])
            ->order('id', "DESC")
            ->limit($where['page'] - 1, $where['limit'])
            ->select();

        return json(['code' => 0, 'data' => $storeList]);
    }

    /**
     * 添加门店设置
     * */
    public function index()
    {
        $store = SystemStoreModel::getStoreDispose();
        $this->assign(compact('store'));
        return $this->fetch();
    }

    /**
     * 位置选择
     * */
    public function select_address()
    {
        $key = sysConfig('tengxun_map_key');
        if (!$key) return $this->failed('请前往设置->系统设置->物流配置 配置腾讯地图KEY', '#');
        $this->assign(compact('key'));
        return $this->fetch();
    }

    /*
     * 保存修改门店信息
     * param int $id
     * */
    public function save($id = 0)
    {

        $data = UtilService::postMore([
            ['name', ''],
            ['introduction', ''],
            ['image', ''],
            ['phone', ''],
            ['address', ''],
            ['detailed_address', ''],
            ['latlng', ''],
            ['valid_time', []],
            ['day_time', []],
        ]);

        $data['province'] = $data['address'][0] ?? '';
        $data['city'] = $data['address'][1] ?? '';
        $data['district'] = $data['address'][2] ?? '';
        try {
            $data['address'] = implode(',', $data['address']);
            $data['latlng'] = is_string($data['latlng']) ? explode(',', $data['latlng']) : $data['latlng'];
            if (!isset($data['latlng'][0]) || !isset($data['latlng'][1])) return JsonService::fail('请选择门店位置');
            $data['latitude'] = $data['latlng'][0];
            $data['longitude'] = $data['latlng'][1];
            $data['valid_time'] = implode(' - ', $data['valid_time']);
            $data['day_time'] = implode(' - ', $data['day_time']);
            unset($data['latlng']);
            if ($data['image'] && strstr($data['image'], 'http') === false) {
                $site_url = SystemConfig::getConfigValue('site_url');
                $data['image'] = $site_url . $data['image'];
            }
            if ($id) {
                if (SystemStoreModel::where('id', $id)->update($data)) {
                    SystemStoreModel::commitTrans();
                    return JsonService::success('修改成功');
                } else {
                    SystemStoreModel::rollbackTrans();
                    return JsonService::fail('修改失败或者您没有修改什么！');
                }
            } else {
                $data['add_time'] = time();
                $data['is_show'] = 1;
                if ($res = SystemStoreModel::create($data)) {
                    SystemStoreModel::commitTrans();
                    return JsonService::success('保存成功', ['id' => $res->id]);
                } else {
                    SystemStoreModel::rollbackTrans();
                    return JsonService::fail('保存失败！');
                }
            }
        } catch (Exception $e) {
            SystemStoreModel::rollbackTrans();
            return JsonService::fail($e->getMessage());
        }
    }


    /**
     * TODO 门店添加和修改
     * @return mixed
     * @throws Exception
     */
    public function create()
    {
        $id = $this->request->param('id');
        $store = new SystemStoreModel();
        $form = Form::make_post_form('添加门店', [], Url::buildUrl('save'), 2);
        $this->assign('form', $form);
        $this->assign('store', $store);
        return $this->fetch();
    }


    /**
     * 保存选择的产品
     * @param int $id
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws Exception
     */
    public function edit($id = 0)
    {
        $store = SystemStoreModel::getStoreDispose($id);
        //dump($store);die;
        $this->assign(compact('store'));
        return $this->fetch();
    }

    public function delivery_config($store_id)
    {
        $storeDeliveryConfigModel = new StoreDeliveryConfig();
        $storeDeliveryConfig = $storeDeliveryConfigModel->with('store')
            ->where('store_id', $store_id)
            ->find();
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $post['weight_amount_list'] = json_encode($post['weight_amount_list']);
            $post['distance_amount_list'] = json_encode($post['distance_amount_list']);
            $post['update_time'] = date('Y-m-d H:i:s');
            //不存在则插入
            if (empty($storeDeliveryConfig)) {
                $post['create_time'] = date('Y-m-d H:i:s');
                $res = $storeDeliveryConfigModel->insertGetId($post);
            } else {
                $res = $storeDeliveryConfigModel->where('store_id', $storeDeliveryConfig['store_id'])->update($post);
            }
            //判断结果
            if ($res) {
                return JsonService::success('修改成功！');
            } else {
                return JsonService::fail('修改失败或者您没有修改什么！');
            }
        }
        if ($storeDeliveryConfig == null) {
            $storeDeliveryConfig = StoreDeliveryConfig::getDefaultConfig($store_id);
        }
        $storeDeliveryConfig['weight_amount_list'] = json_decode($storeDeliveryConfig['weight_amount_list'], true);
        $storeDeliveryConfig['distance_amount_list'] = json_decode($storeDeliveryConfig['distance_amount_list'], true);
        $this->assign(compact('storeDeliveryConfig'));
        return $this->fetch();
    }


    /**
     *软删除门店
     * @param int $id
     * @return string
     * @throws Exception
     */
    public function delete($id = 0)
    {
        $delRes = $this->model->where('id', $id)->update(['is_del' => 1]);
        if ($delRes) {
            return JsonService::success('删除成功');
        } else {
            return JsonService::fail('删除失败');
        }
    }


    /**
     *软删除门店
     * @param int $id
     * @return string
     * @throws Exception
     */
    public function storeStatus($id = 0)
    {
        $existWork = $this->model->where('id', $id)
            ->find();
        if ($existWork) {
            //更新的关联记录
            $status = $existWork['status'] ? 0 : 1; //取反
            $workRes = $this->model->where('id', $id)->update(['update_time' => date('Y-m-d H:i:s'), 'status' => $status]);
            if ($workRes) {
                return JsonService::success('修改成功');
            } else {
                return JsonService::fail('修改失败');
            }
        } else {
            return JsonService::fail('该门店不存在!');
        }
    }


    /**
     *软删除门店
     * @param int $id
     * @return string
     * @throws Exception
     */
    public function storeShow($id = 0)
    {
        $existWork = $this->model->where('id', $id)
            ->find();
        if ($existWork) {
            //更新的关联记录
            $show = $existWork['is_show'] ? 0 : 1; //取反
            $workRes = $this->model->where('id', $id)->update([/*'update_time'=>date('Y-m-d H:i:s'),*/ 'is_show' => $show]);
            if ($workRes) {
                return JsonService::success('修改成功');
            } else {
                return JsonService::fail('修改失败');
            }
        } else {
            return JsonService::fail('该门店不存在!');
        }
    }


    /**
     * 门店的配送员 申请工作
     * @return string
     * @throws Exception
     */
    public function deliverymanApplyList()
    {
        $where = Util::getMore([['real_name', $this->request->param('real_name', '')],
            ['create_time', $this->request->param('create_time', '')]
        ], $this->request);

        $this->assign('where', $where);

        return $this->fetch();
    }

    /**
     * 门店的配送员 工作申请列表 的 接口
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException|Exception
     */
    public function deliverymanApplyListTable()
    {
        $storeModel = $this->model;

        $where = Util::getMore([
            ['page', $this->request->param('page', 1)],
            ['limit', $this->request->param('limit', 10)],
            ['real_name', $this->request->param('real_name', null)],
            ['status', $this->request->param('status', null)],
            ['add_time', $this->request->param('add_time', null)],
            ['storeName', $this->request->param('storeName', null)],
        ], $this->request);


        if ($where['add_time']) {
            $addTimeArray = explode(' - ', $where['add_time']);
            $start = $addTimeArray[0] . ' 00:00:00';
            $end = $addTimeArray[0] . ' 23:59:59';
            $storeModel = $storeModel->whereTime('add_time', 'between', [$start, $end]);
        }
        $page = $this->request->param('page', 1);
        $limit = $this->request->param('limit', 10);
        $applyWork = new DeliverymanApplywork();
        $applyList = $applyWork->with(['store', 'deliveryman'])
            ->order('id', "desc")
            ->limit($page - 1, $limit)
            ->select();

        return json(['code' => 0, 'data' => $applyList]);

    }


    /**
     * 门店的配送员 工作关联
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException|Exception
     */
    public function deliverymanWorkList()
    {
        $where = Util::getMore([
            ['title', ''],
            ['cid', $this->request->param('pid', '')]
        ], $this->request);
        $this->assign('where', $where);

        return $this->fetch();
    }

    /**门店的配送员 工作关联 接口
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function deliverymanWorkListTable()
    {

        $page = $this->request->param('page', 1);
        $limit = $this->request->param('limit', 10);

        $workModel = new DeliverymanWork();
        $workList = $workModel->with(['store', 'deliveryman'])
            ->order('id', "desc")
            ->limit($page - 1, $limit)
            ->select();
        return json(['code' => 0, 'data' => $workList]);
    }


    /**
     * 通过 配送员 门店工作 的申请
     * @param $id
     * @param int $flag
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function passApply($id, $flag = 1)
    {
        $status = null;
        switch ($flag) {
            case 'pass':
                $status = 1;
                break;
            case 'refuse':
                $status = 0;
                break;
            default:
                JsonService::fail('未知的操作');
        }
        $applyWorkModel = new DeliverymanApplywork();
        $workModel = new DeliverymanWork();
        //寻找申请记录
        $applyWorkLog = $applyWorkModel->where('id', $id)
            ->where('is_handled', 0)
            ->find();

        if (!$applyWorkLog) {
            JsonService::fail('未找到相关的申请记录或该记录已经处理');
            exit();
        }
        $updateRes = $applyWorkLog->where('id', $applyWorkLog['id'])->update(['is_handled' => 1, 'apply_status' => $status]);
        if (!$updateRes) {
            return JsonService::fail('更新申请记录失败');
        }
        $existWork = $workModel->where('store_id', $applyWorkLog['store_id'])
            ->where('deliveryman_id', $applyWorkLog['deliveryman_id'])
            ->find();
        if ($existWork) {
            //更新的关联记录
            $workRes = $workModel->where('id', $existWork->id)->update(['status' => $status, 'update_time' => date('Y-m-d H:i:s')]);

        } else {
            //添加 工作 的关联记录
            $workRes = $workModel->insertGetId([
                'store_id' => $applyWorkLog['store_id'],
                'deliveryman_id' => $applyWorkLog['deliveryman_id'],
                'create_time' => date('Y-m-d H:i:s'),
                'status' => $status
            ]);
        }
        if ($workRes) {
            return JsonService::success('修改成功');
        } else {
            return JsonService::fail('修改失败');
        }
    }


    /**
     * //切换 配送员 门店工作 的 启用状态
     * @param $id
     * @param int $flag
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function workStatus($id)
    {
        $workModel = new DeliverymanWork();
        $existWork = $workModel->where('id', $id)
            ->find();
        if ($existWork) {
            //更新的关联记录
            $status = $existWork['status'] ? 0 : 1; //取反
            $workRes = $workModel->where('id', $id)->update(['update_time' => date('Y-m-d H:i:s'), 'status' => $status]);
            if ($workRes) {
                return JsonService::success('修改成功');
            } else {
                return JsonService::fail('修改失败');
            }
        } else {
            return JsonService::fail('工作 关系不存在!');
        }
    }


    /**
     * 删除 配送员 与门店 的  工作关系
     * @param $id
     * @param int $flag
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws Exception
     */
    public function delWorkRelationship($id)
    {
        $workModel = new DeliverymanWork();
        $existWork = $workModel->where('id', $id)
            ->find();
        if ($existWork) {
            //更新的关联记录
            $workRes = $workModel->where('id', $id)->delete();
            if ($workRes) {
                return JsonService::success('修改成功');
            } else {
                return JsonService::fail('修改失败');
            }
        } else {
            return JsonService::fail('工作 关系不存在!');
        }
    }

    /**
     * 获取所有绑定的门店
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getListByAdminBindStore(){
        $adminInfo = Session::get("adminInfo");
        $storeIds = json_decode($adminInfo['store_ids']);
        $storeList = SystemStoreModel::getListByAdminBindStore($storeIds);

        if ($storeList) {
            return Json::successful('获取绑定门店成功!', ['storeList' => $storeList]);
        }
        return Json::fail('获取绑定门店失败');
    }


    /**
     * *  获取所有绑定的门店 下 的 省份
     *
     */
    public  function getProvincesOfBindStore()
    {
        $adminInfo = Session::get("adminInfo");
        $storeIds = json_decode($adminInfo['store_ids'], true);
        $provinceList= SystemStoreModel::getProvincesOfBindStore($storeIds);
        if ($provinceList) {
            return Json::successful('获取省级行政区域成功!', ['provinceList' => $provinceList]);
        }
        return Json::fail('获取省级行政区域失败');
    }

    /**
     *  获取所有绑定的门店 下 的 城市
     * @param string $province
     * @return void
     */
    public  function getCityOfBindStore(string $province)
    {
        $cityList =SystemStoreModel::getCityOfBindStore($province);
        if ($cityList) {
            return Json::successful('获取市级行政区域成功!', ['cityList' => $cityList]);
        }
        return Json::fail('获取市级行政区域失败');
    }

    /**
     *  通过 省份 && 城市 province 获取所有绑定的门店
     * @param string $province
     * @param array $city
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public  function getStoreByCityAndProvince(string $province,array  $city)
    {
        $adminInfo = Session::get("adminInfo");
        $storeIds = json_decode($adminInfo['store_ids'], true);
        $storeList =SystemStoreModel::getStoreByCityAndProvince($province,$city,$storeIds);
        if ($storeList) {
            return Json::successful('获取门店站点成功!', ['storeList' => $storeList]);
        }
        return Json::fail('获取门店站点失败');
    }


}
