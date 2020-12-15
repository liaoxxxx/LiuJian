<?php

namespace app\admin\controller\ump;

use app\admin\controller\AuthController;
use app\admin\model\ump\StoreCouponIssue;
use app\admin\model\wechat\WechatUser as UserModel;
use common\services\FormBuilder as Form;
use common\services\JsonService;
use common\services\UtilService as Util;
use common\services\JsonService as Json;
use common\services\UtilService;
use app\admin\model\ump\StoreCouponGroup as CouponGroupModel;
use think\facade\Route as Url;
use app\enum\EcommerceEnum;

/**
 * 优惠券组控制器
 * Class StoreCouponGroup
 * @package app\admin\controller\ump
 */
// TODO 优惠券数据结构有所变动，这里需要做改动
class StoreCouponGroup extends AuthController
{

    /**
     * @return mixed
     */
    public function index()
    {
        $where = Util::getMore([
            ['status',''],
            ['title',''],
        ],$this->request);
        $this->assign('where',$where);
        $data = CouponGroupModel::systemPage($where);
        $list = $data['list'];
        foreach ($list as $k=>$v) {
            $list[$k]['type'] = EcommerceEnum::COUPON_TYPE_MAP[$v['type']];
            $list[$k]['product_range_type'] = EcommerceEnum::PRODUCT_RANGE_TYPE_MAP[$v['product_range_type']];
            $list[$k]['start_time'] = date('Y-m-d h:i:s',$v['start_time']);
            $list[$k]['expiry_time'] = date('Y-m-d h:i:s',$v['expiry_time']);
        }
        $this->assign('lists',$list);
        $this->assign($data);
        return $this->fetch();
    }

    /**
     * @return mixed
     */
    public function create()
    {
        $f = array();
        $f[] = Form::input('title','优惠券名称');
        $f[] = Form::number('coupon_price','优惠券面值',0)->min(0);
        $f[] = Form::number('limit_amount','优惠券最低消费')->min(0);
        $f[] = Form::idate('start_time','有效期开始时间');
        $f[] = Form::idate('expiry_time','有效期过期时间');
        $f[] = Form::radio('type','优惠券类型',0)->options([['label'=>'无门槛','value'=>0],['label'=>'满减','value'=>1]]);
        $f[] = Form::radio('product_range_type','优惠券使用范围类型',0)->options([['label'=>'全部商品','value'=>0],['label'=>'指定分类','value'=>1],['label'=>'指定商品','value'=>2]]);
        $f[] = Form::text('product_range_value','范围类型值如:[1,2]');
        $f[] = Form::radio('platform','适用平台类型',0)->options([['label'=>'全平台','value'=>0],['label'=>'H5','value'=>1],['label'=>'微信小程序','value'=>2]]);
        $f[] = Form::textarea('notes','特殊说明');
//        $f[] = Form::number('time_limit','优惠券激活后使用期限(天数)')->min(0);
        $f[] = Form::number('sort','排序');
        $f[] = Form::radio('status','状态',0)->options([['label'=>'开启','value'=>1],['label'=>'关闭','value'=>0]]);

        $form = Form::make_post_form('添加优惠券',$f,Url::buildUrl('save'));
        $this->assign(compact('form'));
        return $this->fetch('public/form-builder');
    }

    public function save()
    {
        $data = Util::postMore([
            'title',
            'coupon_price',
            'limit_amount',
            'start_time',
            'expiry_time',
            'type',
            'product_range_type',
            'product_range_value',
            'platform',
            'notes',
            'sort',
            ['status',0]
        ]);
        if(!$data['title']) return Json::fail('请输入优惠券名称');
        if(!$data['coupon_price']) return Json::fail('请输入优惠券面值');
        if(!$data['start_time']) return Json::fail('请输入优惠券有效期限');
        if(!$data['expiry_time']) return Json::fail('请输入优惠券有效期限');
        $data['start_time'] = strtotime($data['start_time']);
        $data['expiry_time'] = strtotime($data['expiry_time']);
        if($data['expiry_time'] < $data['start_time']) return Json::fail('有效期时间有误');
        if($data['limit_amount']>0 && !$data['type']) return Json::fail('无门槛类型优惠券最低消费为0');
        if($data['product_range_value'] && !$data['product_range_type']) return Json::fail('使用范围为全部商品，范围类型值应为空');
        $data['add_time'] = time();
        CouponGroupModel::create($data);
        return Json::successful('添加优惠券成功!');
    }

    /**
     * 显示编辑资源表单页.
     * @param $id
     * @return string|void
     * @throws \FormBuilder\exception\FormBuilderException
     */
    public function edit($id)
    {
        $coupon = CouponGroupModel::get($id);
        if(!$coupon) return Json::fail('数据不存在!');
        $f = array();
        $f[] = Form::input('title','优惠券名称',$coupon->getData('title'));
        $f[] = Form::number('coupon_price','优惠券面值',$coupon->getData('coupon_price'))->min(0);
        $f[] = Form::number('use_min_price','优惠券最低消费',$coupon->getData('use_min_price'))->min(0);
        $f[] = Form::number('coupon_time','优惠券有效期限',$coupon->getData('coupon_time'))->min(0);
        $f[] = Form::number('sort','排序',$coupon->getData('sort'));
        $f[] = Form::radio('status','状态',$coupon->getData('status'))->options([['label'=>'开启','value'=>1],['label'=>'关闭','value'=>0]]);

        $form = Form::make_post_form('添加优惠券',$f,Url::buildUrl('update',array('id'=>$id)));
        $this->assign(compact('form'));
        return $this->fetch('public/form-builder');
    }


    /**
     * 保存更新的资源
     *
     * @param $id
     */
    public function update($id)
    {
        $data = Util::postMore([
            'title',
            'coupon_price',
            'use_min_price',
            'coupon_time',
            'sort',
            ['status',0]
        ]);
        if(!$data['title']) return Json::fail('请输入优惠券名称');
        if(!$data['coupon_price']) return Json::fail('请输入优惠券面值');
        if(!$data['coupon_time']) return Json::fail('请输入优惠券有效期限');
        CouponGroupModel::edit($data,$id);
        return Json::successful('修改成功!');
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        if(!$id) return Json::fail('数据不存在!');
        $data['is_del'] = 1;
        if(!CouponGroupModel::edit($data,$id))
            return Json::fail(CouponGroupModel::getErrorInfo('删除失败,请稍候再试!'));
        else
            return Json::successful('删除成功!');
    }

    /**
     * 修改优惠券状态
     * @param $id
     * @return \think\response\Json
     */
    public function status($id)
    {
        if(!$id) return Json::fail('数据不存在!');
        if(!CouponGroupModel::editIsDel($id))
            return Json::fail(CouponGroupModel::getErrorInfo('修改失败,请稍候再试!'));
        else
            return Json::successful('修改成功!');
    }

    /**
     * @return mixed
     */
    public function grant_subscribe(){
        $where = Util::getMore([
            ['status',''],
            ['title',''],
            ['is_del',0],
        ],$this->request);
        $this->assign('where',$where);
        $this->assign(CouponGroupModel::systemPageCoupon($where));
        return $this->fetch();
    }

    /**
     * @return mixed
     */
    public function grant_all(){
        $where = Util::getMore([
            ['status',''],
            ['title',''],
            ['is_del',0],
        ],$this->request);
        $this->assign('where',$where);
        $this->assign(CouponGroupModel::systemPageCoupon($where));
        return $this->fetch();
    }

    /**
     * @param $id
     */
    public function grant($id){
        $where = Util::getMore([
            ['status',''],
            ['title',''],
            ['is_del',0],
        ],$this->request);
        $nickname = UserModel::where('uid','IN',$id)->column('nickname','uid');
        $this->assign('where',$where);
        $this->assign('uid',$id);
        $this->assign('nickname',implode(',',$nickname));
        $this->assign(CouponGroupModel::systemPageCoupon($where));
        return $this->fetch();
    }

    /**
     * 发布优惠券页面
     * @param $id
     * @return string|void
     * @throws \FormBuilder\exception\FormBuilderException
     */
    public function issue($id)
    {
        if(!CouponGroupModel::be(['id'=>$id,'status'=>1,'is_del'=>0]))
            return $this->failed('发布的优惠劵已失效或不存在!');
        $f = array();
        $f[] = Form::input('id','优惠劵组ID',$id)->disabled(1);
        $f[] = Form::dateTimeRange('range_date','领取时间')->placeholder('不填为永久有效');
        $f[] = Form::number('count','发布数量',0)->min(0);
        $f[] = Form::radio('grant_type','发放方式',2)->options([['label'=>'生成兑换码','value'=>2],['label'=>'用户领取','value'=>3]]);
        $f[] = Form::radio('status','状态',1)->options([['label'=>'正常','value'=>1],['label'=>'关闭','value'=>0]]);

        $form = Form::make_post_form('添加优惠券',$f,Url::buildUrl('update_issue',array('id'=>$id)));
        $this->assign(compact('form'));
        return $this->fetch('public/form-builder');
    }

    public function update_issue($id)
    {
        list($_id,$rangeTime,$count,$grant_type,$status) = UtilService::postMore([
            'id',['range_date',['','']],['count',0],['grant_type',2],['status',0]
        ],null,true);
        if($_id != $id) return JsonService::fail('操作失败,信息不对称');
        if(!$count) $count = 0;
        if(!CouponGroupModel::be(['id'=>$id,'status'=>1,'is_del'=>0])) return JsonService::fail('发布的优惠劵已失效或不存在!');
        if(count($rangeTime)!=2) return JsonService::fail('请选择正确的时间区间');

        list($startTime,$endTime) = $rangeTime;
        if(!$startTime) $startTime = 0;
        if(!$endTime) $endTime = 0;
        if(!$startTime && $endTime) return JsonService::fail('请选择正确的开始时间');
        if($startTime && !$endTime) return JsonService::fail('请选择正确的结束时间');
        if(StoreCouponIssue::setIssue($id,$count,strtotime($startTime),strtotime($endTime),$count,$grant_type,$status))
            return JsonService::successful('发布优惠劵成功!');
        else
            return JsonService::fail('发布优惠劵失败!');
    }


    /**
     * 给分组用户发放优惠券
     */
    public function grant_group(){
        $where = Util::getMore([
            ['status',''],
            ['title',''],
            ['is_del',0],
        ],$this->request);
        $group = UserModel::getUserGroup();
        $this->assign('where',$where);
        $this->assign('group',json_encode($group));
        $this->assign(CouponGroupModel::systemPageCoupon($where));
        return $this->fetch();
    }
    /**
     * 给标签用户发放优惠券
     */
    public function grant_tag(){
        $where = Util::getMore([
            ['status',''],
            ['title',''],
            ['is_del',0],
        ],$this->request);
        $tag = UserModel::getUserTag();;//获取所有标签
        $this->assign('where',$where);
        $this->assign('tag',json_encode($tag));
        $this->assign(CouponGroupModel::systemPageCoupon($where));
        return $this->fetch();
    }
}
