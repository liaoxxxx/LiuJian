<?php

namespace common\services;

use app\models\system\Cache as CacheModel;
use common\exceptions\AuthException;
use common\interfaces\ProviderInterface;
use common\traits\LogicTrait;

/**
 * Class YLYService
 * @package common\services
 */
class YLYService extends HttpService implements ProviderInterface
{
    use LogicTrait;

    /**
     * 开发者创建的应用的应用ID
     * @var string
     */
    protected $client_id;

    /**
     * 开发者创建的应用的秘钥
     * @var string
     */
    protected $apiKey;

    /**
     * 开发者id
     * @var string
     */
    protected $partner;

    /**
     * 易联云token
     * @var null
     */
    protected $access_token = null;

    /**
     * 订单编号
     * @var null
     */
    protected $order_id = null;

    /**
     *  终端号码
     * @var string
     */
    protected $terminal;

    /**
     * 打印内容
     * @var string
     */
    protected $content;

    /**
     * 请求地址
     * @var string
     */
    protected $apiUrl = 'https://open-api.10ss.net/';


    public function register($congig = [])
    {

    }

    /**
     * YLYService constructor.
     */
    protected function __construct()
    {
        $system = SystemConfigService::more(['develop_id', 'printing_api_key', 'printing_client_id', 'terminal_number']);
        $this->partner = $system['develop_id'] ?? '';
        $this->apiKey = $system['printing_api_key'] ?? '';
        $this->client_id = $system['printing_client_id'] ?? '';
        $this->terminal = $system['terminal_number'] ?? '';
        $this->getAccessToken();
    }

    /**
     * 获取AccessToken
     * */
    protected function getAccessToken()
    {
        $this->access_token = CacheModel::getDbCache('YLY_access_token', function () {
            $request = self::postRequest($this->apiUrl . 'oauth/oauth', [
                'client_id' => $this->client_id,
                'grant_type' => 'client_credentials',
                'sign' => strtolower(md5($this->client_id . time() . $this->apiKey)),
                'scope' => 'all',
                'timestamp' => time(),
                'id' => $this->createUuid(),
            ]);
            $request = json_decode($request, true);
            $request['error'] = $request['error'] ?? 0;
            $request['error_description'] = $request['error_description'] ?? '';
            if ($request['error'] == 0 && $request['error_description'] == 'success') {
                return $request['body']['access_token'] ?? '';
            }
            return '';
        }, 20 * 86400);
        if (!$this->access_token)
            throw new AuthException('获取access_token获取失败');
    }

    /**
     * 生成UUID4
     * @return string
     */
    protected function createUuid()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000, mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));
    }

    /**
     * 开始打印订单
     * @param $content
     * @param string $order_id
     * @return bool|mixed
     */
    public function orderPrinting(string $order_id = '', int $errorCount = 0)
    {
        $request = self::postRequest($this->apiUrl . 'print/index', [
            'client_id' => $this->client_id,
            'access_token' => $this->access_token,
            'machine_code' => $this->terminal,
            'content' => $this->content,
            'origin_id' => $order_id ? $order_id : $this->order_id,
            'sign' => strtolower(md5($this->client_id . time() . $this->apiKey)),
            'id' => $this->createUuid(),
            'timestamp' => time()
        ]);
        if ($request === false) return false;
        $request = json_decode($request, true);
        if (isset($request['error']) && in_array($request['error'], [18, 14]) && $errorCount == 0) {
            CacheModel::delectDbCache('YLY_access_token');
            $this->getAccessToken();
            return $this->orderPrinting($order_id, 1);
        }
        return $request;
    }

    /**
     * 删除授权终端
     * @param $machine_code
     * @return bool|mixed4
     */
    public function deletePrinter($machine_code)
    {
        $request = self::postRequest($this->apiUrl . 'printer/deleteprinter', [
            'client_id' => $this->client_id,
            'access_token' => $this->access_token,
            'machine_code' => $machine_code,
            'sign' => strtolower(md5($this->client_id . time() . $this->apiKey)),
            'id' => $this->createUuid(),
            'timestamp' => time(),
        ]);
        if ($request === false) return false;
        return json_decode($request, true);
    }

    /**
     * 设置订单打印模板
     * @param string $name 商家名称
     * @param array $orderInfo 下单时间
     * @param array $product 订单详情
     * @return $this
     */
    public function setContent(string $name, array $orderInfo, array $product)
    {
        $timeYmd = date('Y-m-d', time());
        $timeHis = date('H:i:s', time());
        $goodsStr = '<table><tr><td>商品名称</td><td>数量</td><td>单价</td><td>金额</td></tr>';
        foreach ($product as $item) {
            $goodsStr .= '<tr>';
            if (isset($item['productInfo']['attrInfo'])) {
                $price = $item['productInfo']['attrInfo']['price'] ?? $item['productInfo']['price'] ?? 0;
                $goodsStr .= "<td>{$item['productInfo']['store_name']}</td><td>{$item['cart_num']}</td><td>{$price}</td><td>{$item['truePrice']}</td>";
            } else
                $goodsStr .= "<td>{$item['productInfo']['store_name']}</td><td>{$item['cart_num']}</td><td>{$item['productInfo']['price']}</td><td>{$item['truePrice']}</td>";
            $goodsStr .= '</tr>';
        }
        $goodsStr .= '</table>';
        $this->order_id = $orderInfo['order_id'];
        $count = <<<CONTENT
<FB><center> ** {$name} **</center></FB>
<FH2><FW2>----------------</FW2></FH2>
订单编号：{$orderInfo['order_id']}\r
日    期: {$timeYmd} \r
时    间: {$timeHis}\r
姓    名: {$orderInfo['real_name']}\r
赠送积分: {$orderInfo['gain_integral']}\r
订单备注：{$orderInfo['mark']}\r
*************商品***************\r
{$goodsStr}
********************************\r
<FH>
<LR>合计：￥{$orderInfo['total_price']},优惠: ￥{$orderInfo['coupon_price']}</LR>
<LR>邮费：￥{$orderInfo['pay_postage']},抵扣：￥{$orderInfo['deduction_price']}</LR>
<right>实际支付：￥{$orderInfo['pay_price']}</right>           
</FH>
<FS><center> ** 完 **</center></FS>
CONTENT;
        $this->content = $count;
        return $this;
    }

    /**
     * 获取打印内容
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
}