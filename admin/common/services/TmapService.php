<?php

namespace common\services;

use app\admin\model\store\StoreDeliveryConfig;
use app\admin\model\system\SystemConfig;
use common\utils\LngLat;
use think\console\Command;


/**
 * Class TmapService 腾讯地图服务
 * @package common\services  腾讯地图服务
 */
class TmapService
{

    const MODE = [
        0 => 'driving',
        1 => 'walking',
        2 => 'bicycling'
    ];

    protected $tencentMapKey;

    public function __construct()
    {
        $this->tencentMapKey = SystemConfig::getConfigValue('tengxun_map_key');
    }

    /**
     * 获取路线规划
     * @param LngLat $from
     * @param LngLat $to
     * @param $mode int | TmapService::Mode[$mode]
     * @return bool |string
     */
    public function getRoadGuide(LngLat $from ,LngLat $to, $mode = 2)
    {

        $mode = self::MODE[$mode];
        //echo  $url = "https://apis.map.qq.com/ws/direction/v1/$mode/?from=$startLat,$startLng&to=$endLat,$endLng&key=$this->tencentMapKey";die;
        $url = "https://apis.map.qq.com/ws/direction/v1/$mode/";
        $res = HttpService::getRequest($url,
            [
                'from' => $from->getLat().",".$from->getLng(),
                'to' => $to->getLat().",".$to->getLng(),
                'key' => $this->tencentMapKey
            ]);
        $res = json_decode($res, true);

        if ($res['status'] == 0) {
            return $res;
        } else {
            return null;
        }
    }

    /**
     * 获取路线规划
     * @param LngLat $from
     * @param LngLat $to
     * @param $mode int | TmapService::Mode[$mode]
     * @param bool $isKM
     * @return bool |string
     */
    public function getDistance(LngLat $from ,LngLat $to,  $mode = 2,$isKM =true)
    {
        $guideRes = $this->getRoadGuide( $from , $to, $mode);
        if (isset($guideRes['status']) && $guideRes['status'] == 0) {
            $distance= $guideRes['result']['routes'][0]['distance'];
            if ($isKM){
                $distance /=1000;
            }
            return $distance;
            //dump($guideRes['result']['routes']);die;
        }
        return 0;
    }

    /**
     * @param string $address
     * @return mixed
     */
    public function getLngLatByAddress(string $address)
    {
        $url = "https://apis.map.qq.com/ws/geocoder/v1/";

        $res = HttpService::getRequest($url,
            ['key' => $this->tencentMapKey, 'address' => $address]
        );


        $res = json_decode($res, true);
        if ($res['status'] == 0) {
            $location = $res['result']['location'];
            return new LngLat((float)$location['lng'], (float)$location['lat']);
        } else {
            return new LngLat(0.000000,0.000000);
        }


    }

}
