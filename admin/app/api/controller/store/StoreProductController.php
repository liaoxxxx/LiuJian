<?php

namespace app\api\controller\store;

use app\admin\model\system\SystemAttachment;
use app\models\system\SystemStore;
use app\models\store\StoreProduct;
use app\models\store\StoreProductAttr;
use app\models\store\StoreProductRelation;
use app\models\store\StoreProductReply;
use app\Request;
use common\services\GroupDataService;
use common\services\QrcodeService;
use common\services\SystemConfigService;
use common\services\UtilService;

/**
 * 商品类
 * Class StoreProductController
 * @package app\api\controller\store
 */
class StoreProductController
{
    /**
     * 商品列表
     * @param Request $request
     * @return mixed
     */
    public function lst(Request $request)
    {
        $data = UtilService::getMore([
            [['sid', 'd'], 0],
            [['cid', 'd'], 0],
            ['keyword', ''],
            ['priceOrder', ''],
            ['salesOrder', ''],
            [['news', 'd'], 0],
            [['page', 'd'], 1],
            [['limit', 'd'], 0],
            [['type', 'd'], 0]
        ], $request);
        return app('json')->successful(StoreProduct::getProductList($data, $request->uid()));
    }

    /**
     * 发现商品列表
     * @param Request $request
     * @return mixed
     */
    public function discovery(Request $request)
    {
        $data = UtilService::getMore([
            [['sid', 'd'], 0],
            [['cid', 'd'], 0]
        ], $request);
        return app('json')->successful(StoreProduct::getDiscoveryList($data));
    }

    /**
     * 产品分享二维码 推广员
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function code(Request $request, $id)
    {
        if (!$id || !($storeInfo = StoreProduct::getValidProduct($id, 'id'))) return app('json')->fail('商品不存在或已下架');
        $userType = $request->get('user_type', 'wechat');
        $user = $request->user();
        try {
            switch ($userType) {
                case 'wechat':
                    //公众号
                    $name = $id . '_product_detail_' . $user['uid'] . '_is_promoter_' . $user['is_promoter'] . '_wap.jpg';
                    $url = QrcodeService::getWechatQrcodePath($name, '/detail/' . $id . '?spread=' . $user['uid']);
                    if ($url === false)
                        return app('json')->fail('二维码生成失败');
                    else
                        return app('json')->successful(['code' => UtilService::setImageBase64($url)]);
                    break;
                case 'routine':
                    //小程序
                    $name = $id . '_' . $user['uid'] . '_' . $user['is_promoter'] . '_product.jpg';
                    $imageInfo = SystemAttachment::getInfo($name, 'name');
                    $siteUrl = sysConfig('site_url') . DS;
                    if (!$imageInfo) {
                        $data = 'id=' . $id;
                        if ($user['is_promoter'] || sysConfig('store_brokerage_statu') == 2) $data .= '&pid=' . $user['uid'];
                        $res = \app\models\routine\RoutineCode::getPageCode('pages/goods_details/index', $data, 280);
                        if (!$res) return app('json')->fail('二维码生成失败');
                        $imageInfo = \common\services\UploadService::instance()->setUploadPath('routine/product')->imageStream($name, $res);
                        if (is_string($imageInfo)) return app('json')->fail($imageInfo);
                        if ($imageInfo['image_type'] == 1) $remoteImage = UtilService::remoteImage($siteUrl . $imageInfo['dir']);
                        else $remoteImage = UtilService::remoteImage($imageInfo['dir']);
                        if (!$remoteImage['status']) return app('json')->fail('小程序二维码未能生成');
                        SystemAttachment::attachmentAdd($imageInfo['name'], $imageInfo['size'], $imageInfo['type'], $imageInfo['dir'], $imageInfo['thumb_path'], 1, $imageInfo['image_type'], $imageInfo['time'], 2);
                        $url = $imageInfo['dir'];
                    } else $url = $imageInfo['att_dir'];
                    if ($imageInfo['image_type'] == 1) $url = $siteUrl . $url;
                    return app('json')->successful(['code' => $url]);
            }
        } catch (\Exception $e) {
            return app('json')->fail($e->getMessage(), [
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * 产品详情
     * @param Request $request
     * @param $id
     * @param int $type
     * @return mixed
     */
    public function detail(Request $request, $id, $type = 0)
    {
        if (!$id || !($storeInfo = StoreProduct::getValidProduct($id))) return app('json')->fail('商品不存在或已下架');
        $siteUrl = sysConfig('site_url');
        $storeInfo['image'] = UtilService::setSiteUrl($storeInfo['image'], $siteUrl);
        $storeInfo['image_base'] = UtilService::setSiteUrl($storeInfo['image'], $siteUrl);
//        $storeInfo['code_base'] = QrcodeService::getWechatQrcodePath($id . '_product_detail_wap.jpg', '/detail/' . $id);
        $uid = $request->uid();
        $data['uid'] = $uid;
        //替换windows服务器下正反斜杠问题导致图片无法显示
        $storeInfo['description'] = preg_replace_callback('#<img.*?src="([^"]*)"[^>]*>#i', function ($imagsSrc) {
            return isset($imagsSrc[1]) && isset($imagsSrc[0]) ? str_replace($imagsSrc[1], str_replace('\\', '/', $imagsSrc[1]), $imagsSrc[0]) : '';
        }, $storeInfo['description']);
        if ($uid) {
            StoreProductRelation::setFootProduct($id, $uid, 'foot');
        }
        $storeInfo['userCollect'] = StoreProductRelation::isProductRelation($id, $uid, 'collect');
        $storeInfo['userLike'] = StoreProductRelation::isProductRelation($id, $uid, 'like');
        list($productAttr, $productValue) = StoreProductAttr::getProductAttrDetail($id, $uid, $type);
//        setView($uid, $id, $storeInfo['cate_id'], 'viwe');//相似商品
        $data['storeInfo'] = StoreProduct::setLevelPrice($storeInfo, $uid, true);
//        $data['similarity'] = StoreProduct::cateIdBySimilarityProduct($storeInfo['cate_id'], 'id,store_name,image,price,sales,ficti', 4);//相似商品
        $data['productAttr'] = $productAttr;
        $data['productValue'] = $productValue;
//        $data['priceName'] = 0;
//        if ($uid) {
//            $storeBrokerageStatus = sysConfig('store_brokerage_statu') ?? 1;
//            if ($storeBrokerageStatus == 2)//人人分销
//                $data['priceName'] = StoreProduct::getPacketPrice($storeInfo, $productValue);
//            else {//指定分销
//                $user = $request->user();
//                if ($user->is_promoter)
//                    $data['priceName'] = StoreProduct::getPacketPrice($storeInfo, $productValue);
//            }
//            if (!strlen(trim($data['priceName'])))
//                $data['priceName'] = 0;
//        }
        //商品评价
        $data['reply'] = StoreProductReply::getRecProductReply($storeInfo['id']);
        //商品评价数量
        $data['replyCount'] = StoreProductReply::productValidWhere()->where('product_id', $storeInfo['id'])->count();
        if ($data['replyCount']) {
            $goodReply = StoreProductReply::productValidWhere()->where('product_id', $storeInfo['id'])->where('product_score', 5)->count();
            //好评率
            $data['replyChance'] = $goodReply;
            if ($goodReply) {
                $data['replyChance'] = bcdiv($goodReply, $data['replyCount'], 2);
                $data['replyChance'] = bcmul($data['replyChance'], 100, 2);
            }
        } else $data['replyChance'] = 0;
        $data['mer_id'] = $storeInfo['mer_id'];
//        $data['system_store'] = ($res = SystemStore::getStoreDispose()) ? $res : [];
//        $data['good_list'] = StoreProduct::getGoodList(18, 'image,store_name,price,id,ot_price');
//        $data['mapKey'] = sysConfig('tengxun_map_key');//腾讯地图KEY
        $data['store_self_mention'] = (int)sysConfig('store_self_mention') ?? 0;//门店自提是否开启
        return app('json')->successful($data);
    }

    /**
     * 推荐商品
     *
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getGoodList(Request $request)
    {
        list($limit) = UtilService::getMore([
            [['limit', 'd'], 0]
        ], $request, true);
        $uid = $request->uid();
        if (!$limit) return app('json')->successful([]);
        $good_list = StoreProduct::getGoodList($limit, 'image,store_name,price,id,ot_price');
        $good_list = StoreProduct::setLevelPrice($good_list, $uid);
        return app('json')->successful($good_list);
    }

    /**
     * 为你推荐
     *
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function product_hot(Request $request)
    {
        list($page, $limit) = UtilService::getMore([
            [['page', 'd'], 0],
            [['limit', 'd'], 0]
        ], $request, true);
        if (!$limit) return app('json')->successful([]);
        $productHot = StoreProduct::getHotProductLoading('id,image,store_name,cate_id,price,unit_name,ot_price', (int)$page, (int)$limit);
        return app('json')->successful($productHot);
    }

    /**
     * 获取首页推荐不同类型产品的轮播图和产品
     * @param Request $request
     * @param $type
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function groom_list(Request $request, $type)
    {
        $info['banner'] = [];
        $info['list'] = [];
        if ($type == 1) {//TODO 精品推荐
            $info['banner'] = GroupDataService::getData('routine_home_bast_banner') ?: [];//TODO 首页精品推荐图片
            $info['list'] = StoreProduct::getBestProduct('id,image,store_name,cate_id,price,ot_price,IFNULL(sales,0) + IFNULL(ficti,0) as sales,unit_name,sort');//TODO 精品推荐个数
        } else if ($type == 2) {//TODO  热门榜单
            $info['banner'] = GroupDataService::getData('routine_home_hot_banner') ?: [];//TODO 热门榜单 猜你喜欢推荐图片
            $info['list'] = StoreProduct::getHotProduct('id,image,store_name,cate_id,price,ot_price,unit_name,sort,IFNULL(sales,0) + IFNULL(ficti,0) as sales', 0, $request->uid());//TODO 热门榜单 猜你喜欢
        } else if ($type == 3) {//TODO 首发新品
            $info['banner'] = GroupDataService::getData('routine_home_new_banner') ?: [];//TODO 首发新品推荐图片
            $info['list'] = StoreProduct::getNewProduct('id,image,store_name,cate_id,price,ot_price,unit_name,sort,IFNULL(sales,0) + IFNULL(ficti,0) as sales', 0, $request->uid());//TODO 首发新品
        } else if ($type == 4) {//TODO 促销单品
            $info['banner'] = GroupDataService::getData('routine_home_benefit_banner') ?: [];//TODO 促销单品推荐图片
            $info['list'] = StoreProduct::getBenefitProduct('id,image,store_name,cate_id,price,ot_price,stock,unit_name,sort');//TODO 促销单品
        }
        return app('json')->successful($info);
    }

    /**
     * 产品评价数量和好评度
     * @param $id
     * @return mixed
     */
    public function reply_config($id)
    {
        if (!$id || !is_numeric($id)) return app('json')->fail('参数错误!');
        return app('json')->successful(StoreProductReply::productReplyCount($id));
    }

    /**
     * 获取产品评论
     * @param Request $request
     * @param $id
     * @param $type
     * @return mixed
     */
    public function reply_list(Request $request, $id)
    {
        list($page, $limit, $type) = UtilService::getMore([
            [['page', 'd'], 0],
            [['limit', 'd'], 0],
            [['type', 'd'], 0]
        ], $request, true);
        if (!$id || !is_numeric($id)) return app('json')->fail('参数错误!');
        $list = StoreProductReply::getProductReplyList($id, (int)$type, $page, $limit);
        return app('json')->successful($list);
    }

}