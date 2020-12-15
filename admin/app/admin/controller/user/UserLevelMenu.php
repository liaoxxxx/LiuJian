<?php
namespace app\admin\controller\user;

use app\admin\controller\AuthController;
use common\services\FormBuilder as Form;
use common\services\JsonService;
use common\services\UtilService;
use think\facade\Route as Url;
use common\traits\CurdControllerTrait;
use app\admin\model\user\UserLevelMenu as UserLevelMenuModel;

/**
 * 会员续费套餐
 * Class UserLevelMenu
 * @package app\admin\controller\user
 */
class UserLevelMenu extends AuthController
{
    use CurdControllerTrait;

    /*
     * 套餐展示
     * */
    public function index()
    {
        return $this->fetch();
    }

    /*
     * 创建form表单
     * */
    public function create($id=0)
    {
        if($id) $vipinfo=UserLevelMenuModel::get($id);
        $field[]= Form::number('month','月数',isset($vipinfo) ? $vipinfo->month : 1)->min(1)->col(8);
        $field[]= Form::number('price','售价',isset($vipinfo) ? $vipinfo->price : 0)->min(0)->col(24);
        $field[]= Form::number('discount','折扣价',isset($vipinfo) ? $vipinfo->discount : 0)->min(0)->col(24);
        $field[]= Form::textarea('mark','备注',isset($vipinfo) ? $vipinfo->mark : '');
        $form = Form::make_post_form('添加套餐',$field,Url::buildUrl('save',['id'=>$id]),2);
        $this->assign(compact('form'));
        return $this->fetch('public/form-builder');
    }

    /*
     * 会员等级添加或者修改
     * @param $id 修改的等级id
     * @return json
     * */
    public function save($id=0)
    {
        $data=UtilService::postMore([
            ['month',1],
            ['price',0],
            ['discount',0],
            ['mark',''],
        ]);
        if(!$data['price']) return JsonService::fail('售价不能为0');
        if(!$data['discount']) return JsonService::fail('折扣价价不能为0');
        if(!$id && UserLevelMenuModel::be(['month'=>$data['month'],'is_del'=>0])) return JsonService::fail('已检测到您设置过的套餐，此套餐不可重复');
        UserLevelMenuModel::beginTrans();
        try{
            //修改
            if($id){
                if(UserLevelMenuModel::edit($data,$id)){
                    UserLevelMenuModel::commitTrans();
                    return JsonService::successful('修改成功');
                }else{
                    UserLevelMenuModel::rollbackTrans();
                    return JsonService::fail('修改失败');
                }
            }else{
                //新增
                if(UserLevelMenuModel::create($data)){
                    UserLevelMenuModel::commitTrans();
                    return JsonService::successful('添加成功');
                }else{
                    UserLevelMenuModel::rollbackTrans();
                    return JsonService::fail('添加失败');
                }
            }
        }catch (\Exception $e){
            UserLevelMenuModel::rollbackTrans();
            return JsonService::fail($e->getMessage());
        }
    }
    /*
     * 获取系统设置的会员充值套餐
     * @param int page
     * @param int limit
     * */
    public function get_user_level_menu()
    {
        $where=UtilService::getMore([
            ['page',0],
            ['limit',10]
        ]);
        return JsonService::successlayui(UserLevelMenuModel::getUserlevelMenu($where));
    }

    /*
     * 删除套餐
     * @param int $id
     * */
    public function delete($id=0)
    {
        if(UserLevelMenuModel::edit(['is_del'=>1],$id))
            return JsonService::successful('删除成功');
        else
            return JsonService::fail('删除失败');
    }


}