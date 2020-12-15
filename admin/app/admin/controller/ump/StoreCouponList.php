<?php
/**
 *
 * @author: xaboy<365615158@qq.com>
 * @day: 2018/01/17
 */

namespace app\admin\controller\ump;


use app\admin\controller\AuthController;
use common\services\FormBuilder as Form;
use app\admin\model\ump\StoreCouponList as CouponListModel;
use common\services\JsonService;
use think\facade\Route as Url;
use common\traits\CurdControllerTrait;
use common\services\UtilService as Util;

class StoreCouponList extends AuthController
{
    use CurdControllerTrait;

    protected $bindModel = CouponIssueModel::class;

    /**
     * 显示优惠券兑换码列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $issue_coupon_id = $this->request->param('issue_coupon_id')?? 0;
        $where = Util::getMore([
            ['status',''],
            ['coupon_code',''],
            ['issue_coupon_id',$issue_coupon_id]
        ],$this->request);
        $this->assign(CouponListModel::getCouponPage($where));
        $this->assign('where',$where);
        return $this->fetch();
    }

    /**
     * 显示已领取优惠券列表
     *
     * @return \think\Response
     */
    public function receive()
    {
        $where = Util::getMore([
            ['status',''],
            ['coupon_code','']
        ],$this->request);
        $this->assign(CouponListModel::getReceivePage($where));
        $this->assign('where',$where);
        return $this->fetch();
    }

    /**
     * 删除优惠券兑换码
     * @param string $id
     */
    public function delete($id = '')
    {
        if(!$id) return JsonService::fail('参数有误!');
        if(CouponListModel::edit(['is_del'=>1],$id,'id'))
            return JsonService::successful('删除成功!');
        else
            return JsonService::fail('删除失败!');
    }

    /**
     * 冻结优惠券兑换码
     * @param string $id
     */
    public function frozen($id = '')
    {
        if(!$id) return JsonService::fail('参数有误!');
        if(CouponListModel::edit(['is_frozen'=>1],$id,'id'))
            return JsonService::successful('冻结成功!');
        else
            return JsonService::fail('冻结失败!');
    }

}