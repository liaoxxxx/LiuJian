<?php

namespace app\admin\controller\distribution;

use app\admin\controller\AuthController;
use common\basic\BaseModel;
use common\services\FormBuilder as Form;
use common\services\UtilService as Util;
use common\services\JsonService as Json;
use common\services\UploadService as Upload;
use app\admin\model\article\ArticleCategory as ArticleCategoryModel;
use app\admin\model\article\Article as ArticleModel;
use app\admin\model\system\SystemAttachment;
use app\admin\model\distribution\Deliveryman as DeliverymanModel;
use think\facade\App;
use think\facade\Route as Url;
use think\facade\Session;

/**
 * 图文管理
 * Class WechatNews
 * @package app\admin\controller\wechat
 */
class Deliveryman extends AuthController
{
    private $model;


    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new DeliverymanModel();
    }


    /**
     * TODO 显示后台管理员添加的图文
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $where = Util::getMore([
            ['title', ''],
            ['cid', $this->request->param('pid', '')]
        ], $this->request);
        $this->assign('where', $where);
        // $where['merchant'] = 0;//区分是管理员添加的图文显示  0 还是 商户添加的图文显示  1

        $this->assign(DeliverymanModel::getAll($where));
        return $this->fetch();
    }

    /**
     * TODO 文件添加和修改
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function create()
    {
        $id = $this->request->param('id');
        $cid = $this->request->param('cid');
        $news = [];
        $all = [];
        $news['id'] = '';
        $news['image_input'] = '';
        $news['title'] = '';
        $news['author'] = '';
        $news['is_banner'] = '';
        $news['is_hot'] = '';
        $news['content'] = '';
        $news['synopsis'] = '';
        $news['url'] = '';
        $news['cid'] = [];
        $select = 0;
        if ($id) {
            $news = ArticleModel::where('n.id', $id)->alias('n')->field('n.*,c.content')->join('ArticleContent c', 'c.nid=n.id', 'left')->find();
            if (!$news) return $this->failed('数据不存在!');
            $news['cid'] = explode(',', $news['cid']);
        }
        if ($cid && in_array($cid, ArticleCategoryModel::getArticleCategoryInfo(0, 'id'))) {
            $all = ArticleCategoryModel::getArticleCategoryInfo($cid);
            $select = 1;
        }
        if (!$select) {
            $list = ArticleCategoryModel::getTierList();
            foreach ($list as $menu) {
                $all[$menu['id']] = $menu['html'] . $menu['title'];
            }
        }
        $this->assign('all', $all);
        $this->assign('news', $news);
        $this->assign('cid', $cid);
        $this->assign('select', $select);
        return $this->fetch();
    }

    /**
     * 上传图文图片
     * @return \think\response\Json
     */
    public function upload_image()
    {
        $res = Upload::instance()->setUploadPath('wechat/image/' . date('Ymd'))->image($_POST['file']);
        if (!is_array($res)) return Json::fail($res);
        SystemAttachment::attachmentAdd($res['name'], $res['size'], $res['type'], $res['dir'], $res['thumb_path'], 5, $res['image_type'], $res['time']);
        return Json::successful('上传成功!', ['url' => $res['dir']]);
    }

    /**
     * 添加和修改图文
     */
    public function add_new()
    {
        $data = Util::postMore([
            ['id', 0],
            ['cid', []],
            'title',
            'author',
            'image_input',
            'content',
            'synopsis',
            'share_title',
            'share_synopsis',
            ['visit', 0],
            ['sort', 0],
            'url',
            ['is_banner', 0],
            ['is_hot', 0],
            ['status', 1],]);
        $data['cid'] = implode(',', $data['cid']);
        $content = $data['content'];
        unset($data['content']);
        if ($data['id']) {
            $id = $data['id'];
            unset($data['id']);
            $res = false;
            ArticleModel::beginTrans();
            $res1 = ArticleModel::edit($data, $id, 'id');
            $res2 = ArticleModel::setContent($id, $content);
            if ($res1 && $res2) {
                $res = true;
            }
            ArticleModel::checkTrans($res);
            if ($res)
                return Json::successful('修改图文成功!', $id);
            else
                return Json::fail('修改图文失败，您并没有修改什么!', $id);
        } else {
            $data['add_time'] = time();
            $data['admin_id'] = $this->adminId;
            $res = false;
            ArticleModel::beginTrans();
            $res1 = ArticleModel::create($data);
            $res2 = false;
            if ($res1)
                $res2 = ArticleModel::setContent($res1->id, $content);
            if ($res1 && $res2) {
                $res = true;
            }
            ArticleModel::checkTrans($res);
            if ($res)
                return Json::successful('添加图文成功!', $res1->id);
            else
                return Json::successful('添加图文失败!', $res1->id);
        }
    }

    /**
     * 删除图文
     * @param $id
     * @return \think\response\Json
     */
    public function delete($id)
    {
        $res = ArticleModel::del($id);
        if (!$res)
            return Json::fail('删除失败,请稍候再试!');
        else
            return Json::successful('删除成功!');
    }

    public function merchantIndex()
    {
        $where = Util::getMore([
            ['title', '']
        ], $this->request);
        $this->assign('where', $where);
        $where['cid'] = input('cid');
        $where['merchant'] = 1;//区分是管理员添加的图文显示  0 还是 商户添加的图文显示  1
        $this->assign(ArticleModel::getAll($where));
        return $this->fetch();
    }

    /**
     * 关联文章 id
     * @param int $id
     */
    public function relation($id = 0)
    {
        $this->assign('id', $id);
        return $this->fetch();
    }

    /**
     * 保存选择的产品
     * @param int $id
     */
    public function edit($id = 0)
    {
        if (!$id) {
            return Json::fail('缺少参数');
        }
        $Deliveryman = DeliverymanModel::get($id);
        if (!$Deliveryman) return Json::fail('数据不存在!');
        $f = array();
        $f[] = Form::input('uid', '配送员编号', $Deliveryman->getData('id'))->disabled(1);
        $f[] = Form::input('real_name', '真实姓名', $Deliveryman->getData('real_name'));
        $f[] = Form::input('nickname', '昵称', $Deliveryman->getData('nickname'));
        $f[] = Form::input('phone', '手机号', $Deliveryman->getData('phone'));
        $f[] = Form::input('now_money', '余额', $Deliveryman->getData('now_money'))->disabled(1);
        $f[] = Form::input('card_id', '身份证号', $Deliveryman->getData('card_id'))->disabled(1);
        $f[] = Form::textarea('mark', '用户备注', $Deliveryman->getData('mark'));
        /*$f[] = Form::radio('is_promoter', '推广员', $Deliveryman->getData('is_promoter'))
        ->options([['value' => 1, 'label' => '开启'], ['value' => 0, 'label' => '关闭']]);*/
        $f[] = Form::radio('status', '状态', $Deliveryman->getData('status'))->options([['value' => 1, 'label' => '开启'], ['value' => 0, 'label' => '锁定']]);
        $form = Form::make_post_form('添加用户通知', $f, Url::buildUrl('update', array('id' => $id)), 5);
        $this->assign(compact('form'));
        return $this->fetch('public/form-builder');

    }


    public function update($id)
    {
        $data = Util::postMore([
            ['real_name', ''],
            ['nickname', ''],
            ['phone', ''],
            ['mark', ''],
            ['status', 0],
        ]);

        if (!$id) {
            return $this->failed('数据不存在');
        }
        $user = DeliverymanModel::get($id);
        if (!$user) {
            return Json::fail('数据不存在!');
        }
        BaseModel::beginTrans();
        $res = $this->model->where('id', $id)->update($data);
        BaseModel::checkTrans($res);
        if ($res) return Json::successful('修改成功!');
        else return Json::fail('修改失败');
    }


    /**
     * 获取所有绑定的门店下的所有工作关联的配送员
     */
    public function getListByAdminBindStore()
    {
        $adminInfo = Session::get("adminInfo");
        $storeIds = json_decode($adminInfo['store_ids']);
        $deliverymanList = DeliverymanModel::getListByAdminBindStore($storeIds);
        if ($deliverymanList) {
            return Json::successful('获取绑定门店下的配送员成功!',['deliverymanList'=>$deliverymanList]);
        }
        return Json::fail('获取绑定门店下的配送员失败');
    }


    /**
     * 获取所有绑定的门店下的所有工作关联的配送员
     * @param array $selectStoreIds
     */
    public function getListBySelectStore( array $selectStoreIds)
    {
        $adminInfo = Session::get("adminInfo");
        $bindStoreIds = json_decode($adminInfo['store_ids']);
        $deliverymanList = DeliverymanModel::getListBySelectStore( $bindStoreIds,  $selectStoreIds);
        if ($deliverymanList) {
            return Json::successful('获取绑定门店下的配送员成功!',['deliverymanList'=>$deliverymanList]);
        }
        return Json::fail('获取绑定门店下的配送员失败');
    }

}