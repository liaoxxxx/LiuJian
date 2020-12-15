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
 * Class Discovery
 * @package app\admin\model\discovery
 */
class Discovery extends BaseModel
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
    protected $name = 'discovery';

    use ModelTrait;

    public static function getDiscoveryList($where){
        $model = self::where('is_del',0)->order('sort desc,id desc');
        if(isset($where['title']) && $where['title']!=''){
            $model=$model->where('title','LIKE',"%$where[title]%");
        }
        return self::page($model);
    }

    public static function getDiscoveryProductList(){
        $discovery = self::alias('d')
            ->field('d.id as discovery_id,d.title,d.image as banner')
            ->where('d.is_show',1)
            ->where('d.is_del',0)
            ->order('d.sort desc,d.id desc')->select()->toArray();
        foreach ($discovery as $k=>$v) {
            $product = DiscoveryProduct::alias('dp')
                ->field('dp.product_id')
                ->field('sp.image,sp.store_name,sp.price')
                ->join('store_product sp','sp.id=dp.product_id')
                ->where('discovery_id',$v['discovery_id'])
                ->where('dp.is_show',1)
                ->where('sp.is_del',0)
                ->where('sp.is_show',1)
                ->order('dp.sort desc,dp.id desc')->select()->toArray();
            $discovery[$k]['product_info'] = $product;
        }

        return $discovery;
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

}