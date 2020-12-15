<?php
namespace app\admin\controller\discovery;

use app\admin\controller\AuthController;
use common\services\FormBuilder as Form;
use common\services\JsonService;
use common\services\UtilService as Util;
use common\traits\CurdControllerTrait;
use app\admin\model\discovery\Discovery as DiscoveryModel;
use think\facade\Route as Url;
use common\services\JsonService as Json;
use common\services\CacheService;

/**
 * 发现页商品控制器
 * Class Discovery
 * @package app\admin\controller\discovery
 */
class Discovery extends AuthController
{
    use CurdControllerTrait;
    protected $bindModel = DiscoveryModel::class;

    /**
     * 发现栏目列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $where=Util::getMore([
            ['title','']
        ]);
        $this->assign(DiscoveryModel::getDiscoveryList($where));
        $this->assign('where',$where);
//        $this->assign(DiscoveryModel::getDiscoveryList());
        return $this->fetch();
    }

    /**
     * @return mixed
     */
    public function create()
    {
        $f = array();
        $f[] = Form::input('title','发现页栏目标题');
        $f[] = Form::frameImageOne('image','栏目背景图片',Url::buildUrl('admin/widget.images/index',array('fodder'=>'image')))->icon('image')->width('100%')->height('500px');
        $f[] = Form::number('sort','排序值')->min(0);
        $f[] = Form::radio('is_show','是否展示',0)->options([['label'=>'展示','value'=>1],['label'=>'隐藏','value'=>0]])->col(8);

        $form = Form::make_post_form('添加发现栏目',$f,Url::buildUrl('save'));
        $this->assign(compact('form'));
        return $this->fetch('public/form-builder');
    }

    public function save()
    {
        $data = Util::postMore([
            'title',
            'image',
            'is_show',
            'sort'
        ]);
        if(!$data['title']) return Json::fail('请输入栏目标题');
        if(!$data['image']) return Json::fail('请上传栏目背景图片');
        $data['add_time'] = time();
        DiscoveryModel::create($data);
        return Json::successful('添加发现页栏目成功!');
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
        $Info = DiscoveryModel::get($id);
        if(-1 == $Info['is_show'] || 1 == $Info['is_del']) return $this->failed('状态错误,无法修改');
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
        $info = DiscoveryModel::get($id);
        if(!$info) return Json::fail('数据不存在!');
        $f = array();
        $f[] = Form::input('title','标题',$info['title']);
        $f[] = Form::frameImageOne('image','栏目背景图片',Url::buildUrl('admin/widget.images/index',array('fodder'=>'image')),$info['image'])->icon('image')->width('100%')->height('500px');
        $f[] = Form::number('sort','排序值',$info['sort'])->min(0);
        $f[] = Form::radio('is_show','是否展示',$info['is_show'])->options([['label'=>'展示','value'=>1],['label'=>'隐藏','value'=>0]])->col(8);
        $form = Form::make_post_form('修改订单',$f,Url::buildUrl('update',array('id'=>$id)));
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
            'title',
            'image',
            'sort',
            'is_show'
        ]);
        if(!$data['title']) return Json::fail('请输入标题');
        if(!$data['image']) return Json::fail('请上传栏目背景图片');
        DiscoveryModel::edit($data,$id);
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
        if(!DiscoveryModel::del($id))
            return Json::fail(DiscoveryModel::getErrorInfo('删除失败,请稍候再试!'));
        else {
            CacheService::clear();
            return Json::successful('删除成功!');
        }
    }
}
