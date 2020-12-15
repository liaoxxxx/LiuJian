<?php
namespace app\admin\controller\setting;

use app\admin\controller\AuthController;
use app\admin\model\discovery\Discovery as DiscoveryModel;
use common\services\FormBuilder as Form;
use common\services\JsonService;
use common\services\JsonService as Json;
use common\services\UtilService as Util;
use common\traits\CurdControllerTrait;
use app\admin\model\system\SystemGroupDataProduct as GroupDataProductModel;
use app\admin\model\system\SystemGroupData as GroupDataModel;
use think\facade\Route as Url;
use common\services\CacheService;

/**
 * 发现页商品控制器
 * Class SystemGroupDataProduct
 * @package app\admin\controller\setting
 */
class SystemGroupDataProduct extends AuthController
{
    use CurdControllerTrait;
    protected $bindModel = DiscoveryProductModel::class;

    /**
     * 商品列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $where=Util::getMore([
            ['group_data_id',0],
            ['store_name',''],
        ]);
        $this->assign(GroupDataProductModel::getGroupDataProductList($where));
        $this->assign('where',$where);
        return $this->fetch();
    }

    /**
     * 批量推荐发现商品
     * @return mixed
     */
    public function batch_rec()
    {
        $data=Util::postMore([
            ['ids',[]],
            ['group_data_id',0]
        ]);
        $f = array();

        $f[] = Form::radio('is_show','是否展示',0)->options([['label'=>'展示','value'=>1],['label'=>'隐藏','value'=>0]])->col(8);

        $form = Form::make_post_form('添加发现页商品',$f,Url::buildUrl('batch_save',array('ids'=>$data['ids'],'group_data_id'=>$data['group_data_id'])));
        $this->assign(compact('form'));
        return $this->fetch('public/form-builder');
    }

    public function batch_save()
    {
        $data = Util::postMore([
            'group_data_id',
            'ids',
            'is_show'
        ]);
        if(!$data['ids']) return Json::fail('请输入商品id');
        $data['add_time'] = time();
        $res = GroupDataProductModel::batch_create($data);
        if(!$res) return Json::fail('添加失败!');
        return Json::successful('添加成功!');
    }

    /**
     * 是否展示
     * @param string $id
     * @return string|void
     * @throws \FormBuilder\exception\FormBuilderException
     */
    public function is_show($id = '')
    {
        if(!$id) return JsonService::fail('参数有误!');
        $Info = DiscoveryProductModel::get($id);
        $f = [Form::radio('is_show','是否显示',$Info['is_show'])->options([['label'=>'显示','value'=>1],['label'=>'隐藏','value'=>0]])];
        $form = Form::make_post_form('状态修改',$f,Url::buildUrl('change_field',array('id'=>$id,'field'=>'is_show')));
        $this->assign(compact('form'));
        return $this->fetch('public/form-builder');
    }

    /**
     * 编辑页面
     * @param $id
     * @return mixed|\think\response\Json|void
     */
    public function edit($id)
    {
        if(!$id) return $this->failed('数据不存在');
        $info = GroupDataProductModel::get($id);
        if(!$info) return Json::fail('数据不存在!');
        $f = array();
        $f[] = Form::number('sort','排序值',$info['sort'])->min(0);
        $f[] = Form::radio('is_show','是否展示',$info['is_show'])->options([['label'=>'展示','value'=>1],['label'=>'隐藏','value'=>0]])->col(8);
        $form = Form::make_post_form('编辑',$f,Url::buildUrl('update',array('id'=>$id)));
        $this->assign(compact('form'));
        return $this->fetch('public/form-builder');

    }

    /**
     * 修改提交更新
     * @param $id
     */
    public function update($id)
    {
        $data = Util::postMore([
            'sort',
            'is_show'
        ]);
        GroupDataProductModel::edit($data,$id);
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
        if(!GroupDataProductModel::del($id))
            return Json::fail(GroupDataProductModel::getErrorInfo('删除失败,请稍候再试!'));
        else {
//            CacheService::clear();
            return Json::successful('删除成功!');
        }
    }
}
