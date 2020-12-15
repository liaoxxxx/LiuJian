<?php

namespace common\services;


use common\utils\LngLat;
use Location\Coordinate;
use Location\Distance\Vincenty;
use think\console\Command;


/**
 * Class TmapService 基础地图服务
 * @package common\services
 */
class MapService extends Command
{

    /**
     * 根据起点坐标和终点坐标测距离
     * @param LngLat $from [起点坐标(经纬度),例如: new LngLat(118.012951,36.810024) ]
     * @param LngLat $to [终点坐标(经纬度)]
     * @param bool $km 是否以公里为单位 false:米 true:公里(千米)
     * @param int $decimal 精度 保留小数位数D
     * @return string  距离数值
     */
    public static function getDistance(LngLat $from, LngLat $to, $km = true, $decimal = 2)
    {

        $coordinate1 = new Coordinate($from->getLat(), $from->getLng()); // Mauna Kea Summit
        $coordinate2 = new Coordinate($to->getLat(),$to->getLng()); // Haleakala Summit

        $distance =$coordinate1->getDistance($coordinate2, new Vincenty());

        if ($km){
            return round($distance/1000, $decimal);
        }else{
            return round($distance, $decimal);
        }

    }

}
