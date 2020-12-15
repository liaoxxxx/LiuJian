<?php
/**
 *
 * @author: xaboy<365615158@qq.com>
 * @day: 2017/12/13
 */

namespace app\models\store;

use common\basic\BaseModel;
use think\facade\Db;
use common\traits\ModelTrait;

/**
 * TODO  产品标签Model
 * Class StoreProductLabel
 * @package app\models\store
 */
class StoreProductLabel extends BaseModel
{
    /**
     * 模型名称
     * @var string
     */
    protected $name = 'store_product_label';

    use ModelTrait;

    public static function getLabels($ids)
    {
        return self::storeProductLabelDb()->whereIn('product_id',$ids)->select()->toArray();
    }

    public static function getLabel($id)
    {
        return self::storeProductLabelDb()->where('product_id',$id)->select()->toArray();
    }

    public static function storeProductLabelDb()
    {
        return Db::name('storeProductLabel');
    }


}