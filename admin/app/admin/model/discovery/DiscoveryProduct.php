<?php
/**
 * @author: xaboy<365615158@qq.com>
 * @day: 2017/11/11
 */

namespace app\admin\model\discovery;

use common\traits\ModelTrait;
use common\basic\BaseModel;
use common\services\UtilService;

/**
 * Class DiscoveryProduct
 * @package app\admin\model\discovery
 */
class DiscoveryProduct extends BaseModel
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
    protected $name = 'discovery_product';

    use ModelTrait;

    public static function getDiscoveryProductList($where){
        $model = self::alias('dp')
            ->field('dp.id,dp.product_id,dp.discovery_id,dp.sort,dp.is_show')
            ->field('sp.image,sp.store_name,sp.price')
            ->join('store_product sp','sp.id=dp.product_id')
            ->where('dp.discovery_id',$where['discovery_id'])
            ->where('sp.is_del',0)
            ->order('dp.is_show desc,dp.sort desc,dp.id desc');

        if(isset($where['store_name']) && $where['store_name']!=''){
            $model=$model->where('sp.store_name','LIKE',"%$where[store_name]%");
        }
        return self::page($model);
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
            $is_exits = self::whereIn('product_id',$ids)->where('discovery_id',$data['discovery_id'])->select()->toArray();
            if ($is_exits) return false;
            self::beginTrans();
            foreach ($ids as $Id){
                $res = $res && self::create([
                    'discovery_id'=>$data['discovery_id'],
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