<?php
/**
 * @author: xaboy<365615158@qq.com>
 * @day: 2017/11/11
 */

namespace app\admin\model\system;

use common\traits\ModelTrait;
use common\basic\BaseModel;
use common\services\UtilService;

/**
 * Class SystemGroupDataProduct
 * @package app\admin\model\system
 */
class SystemGroupDataProduct extends BaseModel
{

    /**
     * 数据表主键
     * @var string
     */
    protected $pk = 'id';

    /**
     * 模型名称
     * @var string
     */
    protected $name = 'system_group_data_product';

    use ModelTrait;

    /**
     * 获取商品
     * @param $where
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getGroupDataProductList($where){
        $model = self::alias('gp')
            ->field('gp.*')
            ->field('sp.image,sp.store_name,sp.price')
            ->join('store_product sp','sp.id=gp.product_id')
            ->where('gp.group_data_id',$where['group_data_id'])
            ->where('sp.is_del',0)
            ->order('gp.is_show desc,gp.sort desc,gp.id desc');

        if(isset($where['store_name']) && $where['store_name']!=''){
            $model=$model->where('sp.store_name','LIKE',"%$where[store_name]%");
        }
        return self::page($model);
    }

    /**
     * 获取商品
     * @param $group_data_id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getGroupDataProducts($group_data_id){
        $model = self::alias('gp')
            ->field('gp.id,gp.group_data_id,gp.product_id')
            ->field('sp.image,sp.store_name,sp.price')
            ->join('store_product sp','sp.id=gp.product_id')
            ->where('gp.group_data_id',$group_data_id['group_data_id'])
            ->where('sp.is_del',0)
            ->order('gp.is_show desc,gp.sort desc,gp.id desc');

        return $model->select()->toArray();
    }

    /**
     * 修改一条数据
     * @param $data
     * @param $id
     * @param $field
     * @return bool $type 返回成功失败
     */
    public static function edit($data, $id, $field = null)
    {
        $model = new self;
        if (!$field) $field = $model->getPk();
//        return false !== $model->update($data,[$field=>$id]);
//        return 0 < $model->update($data,[$field=>$id])->result;
        $res = $model->update($data, [$field => $id]);
        if (isset($res->result))
            return 0 < $res->result;
        else if (isset($res['data']['result']))
            return 0 < $res['data']['result'];
        else
            return false !== $res;
    }

    /**
     * 删除一条数据
     * @param $id
     * @return bool $type 返回成功失败
     */
    public static function del($id)
    {
        return false !== self::destroy($id);
    }

    /**
     * 批量新增
     * @param $data
     * @return bool $type 返回成功失败
     */
    public static function batch_create($data)
    {
        $res = true;
        if($data['ids'][0]){
            $ids = explode(',',$data['ids'][0]);
            $is_exits = self::whereIn('product_id',$ids)->where('group_data_id',$data['group_data_id'])->select()->toArray();
            if ($is_exits) return false;
            self::beginTrans();
            foreach ($ids as $Id){
                $res = $res && self::create([
                    'group_data_id'=>$data['group_data_id'],
                    'product_id'=>$Id,
                    'is_show'=>$data['is_show']
                        ]);
            }
            self::checkTrans($res);
            return $res;
        }
        return $res;
    }

}