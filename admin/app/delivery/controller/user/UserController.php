<?php
namespace app\delivery\controller\user;

use app\models\delivery\Deliveryman;
use app\delivery\controller\DeliveryBasic;
use app\http\validates\user\AddressValidate;
use think\exception\ValidateException;
use app\Request;
use app\models\user\User;
use app\models\user\UserAddress;
use common\services\UtilService;
use think\facade\App;

/**
 * 用户类
 * Class UserController
 * @package app\api\controller\store
 */
class UserController extends DeliveryBasic
{

    protected  $model;


    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model=new Deliveryman();
    }

    /**
     * 获取用户信息
     * @param Request $request
     * @return mixed
     */
    public function userInfo(Request $request)
    {
        return app('json')->success($request->user()->toArray());
    }


    /**
     * @param int $isWorking 工作状态 0 ：下线；1： 工作中
     * @return mixed
     * @throws \Exception
     */
    public  function  transWorkStatus($isWorking=0){
        $isWorking=intval($isWorking);
        if ($isWorking) {
            $isReceiving=1;
        }else{
            $isReceiving=0;
        }
        $data=['is_receiving'=>$isReceiving];
        $res=$this->model->where('id',$this->deliveryMan->id)->update($data);
        if ($res){
            return app('json')->success("切换工作状态成功");

        }else{
            return app('json')->fail("切换工作状态失败");
        }
    }


    /**
     * 配送員用户收入统计
     * @param int $startTime
     * @param int $endTime
     * @param int $storeId
     * @return mixed
     * @throws \Exception
     */
    public function workStat( $startTime = 0, $endTime = 0, $storeId=0)
    {
        $deliverymanStat= $this->model-> workStat($this->deliveryMan->id ,$startTime , $endTime, $storeId);
        if ($deliverymanStat){
            return app('json')->successful('获取工作统计数据成功',['deliverymanStat' => $deliverymanStat]);
        }
        return app('json')->fail('获取工作统计数据失败');

    }


    /**
     * 申请门店的配送员 工作
     * @param int $storeId
     * @param string $deliverymanMark
     * @return array|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function applyWorkStore(int $storeId =0 ,$deliverymanMark='申请配送工作')
    {
        return  $this->model->applyWorkStore($storeId,$this->deliveryMan->id,$deliverymanMark);
    }

    /**
     * 地址 获取单个
     * @param Request $request
     * @param $id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function address(Request $request, $id)
    {
        $addressInfo = [];
        if($id && is_numeric($id) && UserAddress::be(['is_del'=>0,'id'=>$id,'uid'=>$request->uid()])){
            $addressInfo = UserAddress::find($id)->toArray();
        }
        return app('json')->successful($addressInfo);
    }

    /**
     * 地址列表
     * @param Request $request
     * @param $page
     * @param $limit
     * @return mixed
     */
    public function address_list(Request $request)
    {
        list($page, $limit) = UtilService::getMore([['page',0], ['limit',20]],$request, true);
        $list = UserAddress::getUserValidAddressList($request->uid(),$page,$limit,'id,real_name,phone,province,city,district,detail,is_default');
        return app('json')->successful($list);
    }

    /**
     * 设置默认地址
     *
     * @param Request $request
     * @return mixed
     */
    public function address_default_set(Request $request)
    {
        list($id) = UtilService::getMore([['id',0]],$request, true);
        if(!$id || !is_numeric($id)) return app('json')->fail('参数错误!');
        if(!UserAddress::be(['is_del'=>0,'id'=>$id,'uid'=>$request->uid()]))
            return app('json')->fail('地址不存在!');
        $res = UserAddress::setDefaultAddress($id,$request->uid());
        if(!$res)
            return app('json')->fail('地址不存在!');
        else
            return app('json')->successful();
    }

    /**
     * 获取默认地址
     * @param Request $request
     * @return mixed
     */
    public function address_default(Request $request)
    {
        $defaultAddress = UserAddress::getUserDefaultAddress($request->uid(),'id,real_name,phone,province,city,district,detail,is_default');
        if($defaultAddress) {
            $defaultAddress = $defaultAddress->toArray();
            return app('json')->successful('ok',$defaultAddress);
        }
        return app('json')->successful('empty',[]);
    }

    /**
     * 修改 添加地址
     * @param Request $request
     * @return mixed
     */
    public function address_edit(Request $request)
    {
        $addressInfo = UtilService::postMore([
            ['address',[]],
            ['is_default',false],
            ['real_name',''],
            ['post_code',''],
            ['phone',''],
            ['detail',''],
            ['id',0]
        ], $request);
        if(!isset($addressInfo['address']['province'])) return app('json')->fail('收货地址格式错误!');
        if(!isset($addressInfo['address']['city'])) return app('json')->fail('收货地址格式错误!');
        if(!isset($addressInfo['address']['district'])) return app('json')->fail('收货地址格式错误!');
        $addressInfo['province'] = $addressInfo['address']['province'];
        $addressInfo['city'] = $addressInfo['address']['city'];
        $addressInfo['district'] = $addressInfo['address']['district'];
        $addressInfo['is_default'] = (int)$addressInfo['is_default'] == true ? 1 : 0;
        $addressInfo['uid'] = $request->uid();
        unset($addressInfo['address']);
        try {
            validate(AddressValidate::class)->check($addressInfo);
        } catch (ValidateException $e) {
            return app('json')->fail($e->getError());
        }
        if($addressInfo['id'] && UserAddress::be(['id'=>$addressInfo['id'],'uid'=>$request->uid(),'is_del'=>0])){
            $id = $addressInfo['id'];
            unset($addressInfo['id']);
            if(UserAddress::edit($addressInfo,$id,'id')){
                if($addressInfo['is_default'])
                    UserAddress::setDefaultAddress($id,$request->uid());
                return app('json')->successful();
            }else
                return app('json')->fail('编辑收货地址失败!');
        }else{
            $addressInfo['add_time'] = time();
            if($address = UserAddress::create($addressInfo))
            {
                if($addressInfo['is_default'])
                {
                    UserAddress::setDefaultAddress($address->id,$request->uid());
                }
                return app('json')->successful(['id'=>$address->id]);
            }else{
                return app('json')->fail('添加收货地址失败!');
            }
        }
    }

    /**
     * 删除地址
     *
     * @param Request $request
     * @return mixed
     */
    public function address_del(Request $request)
    {
        list($id) = UtilService::postMore([['id',0]], $request, true);
        if(!$id || !is_numeric($id)) return app('json')->fail('参数错误!');
        if(!UserAddress::be(['is_del'=>0,'id'=>$id,'uid'=>$request->uid()]))
            return app('json')->fail('地址不存在!');
        if(UserAddress::edit(['is_del'=>'1'],$id,'id'))
            return app('json')->successful();
        else
            return app('json')->fail('删除地址失败!');
    }




    /**
     * 用户修改信息
     * @param Request $request
     * @return mixed
     */
    public function edit(Request $request)
    {
        list($avatar,$nickname) = UtilService::postMore([
            ['avatar',''],
            ['nickname',''],
        ],$request,true);
        if(User::editUser($avatar,$nickname,$request->uid())) return app('json')->successful('修改成功');
        return app('json')->fail('修改失败');
    }

    /**
     * 推广人排行
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function rank(Request $request){
        $data = UtilService::getMore([
            ['page',''],
            ['limit',''],
            ['type','']
        ],$request);
        $users = User::getRankList($data);
        return app('json')->success($users);
    }

    /**
     * 佣金排行
     * @param Request $request
     * @return mixed
     */
    public function brokerage_rank(Request $request){
        $data = UtilService::getMore([
            ['page',''],
            ['limit'],
            ['type']
        ],$request);
        return app('json')->success([
            'rank'    => User::brokerageRank($data),
            'position'=> User::currentUserRank($data['type'],$request->user()['brokerage_price'])
        ]);

    }

}