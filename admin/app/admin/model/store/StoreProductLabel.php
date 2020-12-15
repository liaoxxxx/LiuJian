<?php
/**
 * @author: xaboy<365615158@qq.com>
 * @day: 2017/12/08
 */

namespace app\admin\model\store;

use common\basic\BaseModel;
use common\traits\ModelTrait;

class StoreProductLabel extends BaseModel
{

    /**
     * 模型名称
     * @var string
     */
    protected $name = 'store_product_label';

    use ModelTrait;

    protected function setLabelValuesLabel($value)
    {
        return is_array($value) ? implode(',',$value) : $value;
    }

    protected function getLabelValuesLabel($value)
    {
        return explode(',',$value);
    }


    public static function createProductLabel($LabelList,$productId)
    {
        $LabelNameList = [];
        foreach ($LabelList as $index=>$Label){
            if(!isset($Label['label_name'])) return self::setErrorInfo('请输入标签名称!');
            $Label['label_name'] = trim($Label['label_name']);
            if(!isset($Label['label_name'])) return self::setErrorInfo('请输入标签名称!!');
            $LabelNameList[] = $Label['label_name'];
            $LabelList[$index] = $Label;
        }
        $LabelGroup = [];
        foreach ($LabelList as $k=>$value){
            $LabelGroup[] = [
                'product_id'=>$productId,
                'label_name'=>$value['label_name']
            ];
        }

        if(!count($LabelGroup)) return self::setErrorInfo('请设置至少一个标签!');
        $LabelModel = new self;
        self::beginTrans();
        if(!self::clearProductLabel($productId)) return false;
        $res = false !== $LabelModel->saveAll($LabelGroup);
        self::checkTrans($res);
        if($res)
            return true;
        else
            return self::setErrorInfo('编辑商品标签失败!');
    }

    public static function clearProductLabel($productId)
    {
        if (empty($productId) && $productId != 0) return self::setErrorInfo('商品不存在!');
        $res = false !== self::where('product_id',$productId)->delete();
        if(!$res)
            return self::setErrorInfo('编辑标签失败,清除旧标签失败!');
        else
            return true;
    }

    /**
     * 获取产品标签
     * @param $productId
     * @return array|bool|null|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getStoreProductLabel($productId)
    {
        if (empty($productId) && $productId != 0) return self::setErrorInfo('商品不存在!');
        $count = self::where('product_id',$productId)->count();
        if(!$count) return self::setErrorInfo('商品不存在!');
        return self::where('product_id',$productId)->select()->toArray();
    }

}