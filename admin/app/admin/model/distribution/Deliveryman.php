<?php

/**
 *
 * @author: xaboy<365615158@qq.com>
 * @day: 2017/11/02
 */

namespace app\admin\model\distribution;

use app\admin\model\store\StoreProduct;
use app\admin\model\system\SystemAdmin;
use app\models\delivery\DeliverymanWork;
use common\traits\ModelTrait;
use common\basic\BaseModel;
use think\Collection;
use think\facade\Db;

/**
 * 图文管理 Model
 * Class WechatNews
 * @package app\admin\model\wechat
 */
class Deliveryman extends BaseModel
{

    use ModelTrait;

    protected $pk = 'id';

    protected $name = 'deliveryman';


    public function profile()
    {
        return $this->hasOne(StoreProduct::class, 'id', 'product_id')->field('store_name');
    }

    /**
     * 获取配置分类
     * @param array $where
     * @return array
     */
    public static function getAll($where = array())
    {
        $model = new self;
        $model = $model->order('id desc');
        return self::page($model, function ($item) {
            if (!$item['mer_id']) $item['admin_name'] = '总后台管理员---》' . SystemAdmin::where('id', $item['admin_id'])->value('real_name');
            else $item['admin_name'] = Merchant::where('id', $item['mer_id'])->value('mer_name') . '---》' . MerchantAdmin::where('id', $item['admin_id'])->value('real_name');
            $item['content'] = Db::name('ArticleContent')->where('nid', $item['id'])->value('content');
            $item['catename'] = Db::name('ArticleCategory')->where('id', $item['cid'])->value('title');
            $item['store_name'] = $item->profile->store_name ?? '';
        }, $where);
    }

    /**
     * 删除图文
     * @param $id
     * @return bool
     */
    public static function del($id)
    {
        return self::edit(['status' => 0], $id, 'id');
    }

    /**
     * 获取指定字段的值
     * @return array
     */
    public static function getNews()
    {
        return self::where('status', 1)->where('hide', 0)->order('id desc')->column('title', 'id');
    }

    /**
     * 给表中的字符串类型追加值
     * 删除所有有当前分类的id之后重新添加
     * @param $cid
     * @param $id
     * @return bool
     */
    public static function saveBatchCid($cid, $id)
    {
        $res_all = self::where('cid', 'LIKE', "%$cid%")->select();//获取所有有当前分类的图文
        foreach ($res_all as $k => $v) {
            $cid_arr = explode(',', $v['cid']);
            if (in_array($cid, $cid_arr)) {
                $key = array_search($cid, $cid_arr);
                array_splice($cid_arr, $key, 1);
            }
            if (empty($cid_arr)) {
                $data['cid'] = 0;
                self::edit($data, $v['id']);
            } else {
                $data['cid'] = implode(',', $cid_arr);
                self::edit($data, $v['id']);
            }
        }
        $res = self::where('id', 'IN', $id)->select();
        foreach ($res as $k => $v) {
            if (!in_array($cid, explode(',', $v['cid']))) {
                if (!$v['cid']) {
                    $data['cid'] = $cid;
                } else {
                    $data['cid'] = $v['cid'] . ',' . $cid;
                }
                self::edit($data, $v['id']);
            }
        }
        return true;
    }

    public static function setContent($id, $content)
    {
        $count = Db::name('ArticleContent')->where('nid', $id)->count();
        $data['nid'] = $id;
        $data['content'] = $content;
        if ($count) {
            $contentSql = Db::name('ArticleContent')->where('nid', $id)->value('content');
            if ($contentSql == $content) $res = true;
            else $res = Db::name('ArticleContent')->where('nid', $id)->update(['content' => $content]);
            if ($res !== false) $res = true;
        } else {
            $res = Db::name('ArticleContent')->insert($data);
        }
        return $res;
    }

    public static function merchantPage($where = array())
    {
        $model = new self;
        if ($where['title'] !== '') $model = $model->where('title', 'LIKE', "%$where[title]%");
        if ($where['cid'] !== '') $model = $model->where('cid', 'LIKE', "%$where[cid]%");
        $model = $model
            ->where('status', 1)
            ->where('hide', 0)
            ->where('admin_id', $where['admin_id'])
            ->where('mer_id', $where['mer_id']);
        return self::page($model, function ($item) {
            $item['content'] = Db::name('ArticleContent')->where('nid', $item['id'])->value('content');
        }, $where);
    }

    /**
     * 通过 管理员绑定的门店id  找到他门店下面的 配送员
     * @param array $storeIds
     * @return false|\PDOStatement|string|Collection
     */
    public static function getListByAdminBindStore(array $storeIds)
    {
        if (count($storeIds)) {

            $deliverymanList = DeliverymanWork::alias('dw')
                ->distinct(true)
                ->field('dw.deliveryman_id,d.*')
                ->join('deliveryman d', 'd.id=dw.deliveryman_id')
                ->where('dw.status', 1)
                ->whereIn('dw.store_id',$storeIds)
                ->select();
        } else {
            $deliverymanList =  DeliverymanWork::alias('dw')
                ->distinct(true)
                ->field('dw.deliveryman_id,d.*')
                ->join('deliveryman d', 'd.id=dw.deliveryman_id')
                ->where('dw.status', 1)
                ->select();
        }
        return $deliverymanList;
    }


    /**
     * 通过 管理员绑定的门店id  找到他门店下面的 配送员
     * @param array $bindStoreIds
     * @param array $selectStoreIds
     * @return false|\PDOStatement|string|Collection
     */
    public static function getListBySelectStore(array $bindStoreIds, array $selectStoreIds)
    {

        if (count($bindStoreIds)) {
            //取交集
            $selectStoreIds= array_intersect($bindStoreIds,$selectStoreIds);
            //dump($selectStoreIds);die;
            $deliverymanList = DeliverymanWork::alias('dw')
                ->distinct(true)
                ->field('dw.deliveryman_id,d.*')
                ->join('deliveryman d', 'd.id=dw.deliveryman_id')
                ->where('dw.status', 1)
                ->whereIn('dw.store_id',$selectStoreIds)
                ->select();
        } else {
            $deliverymanList = DeliverymanWork::alias('dw')
                ->distinct(true)
                ->field('dw.deliveryman_id,d.*')
                ->join('deliveryman d', 'd.id=dw.deliveryman_id')
                ->where('dw.status', 1)
                ->whereIn('dw.store_id',$selectStoreIds)
                ->select();
        }
        return $deliverymanList;
    }
}