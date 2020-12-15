<?php

namespace common\utils;


/**
 * 经纬度坐标
 * Class LngLat
 * @package common\utils
 */
class LngLat
{
    /**
     * @var double  纬度
     */
    protected   $lat = 0.00;

    /**
     * @var double  经度
     */
    protected   $lng = 0.00;


    public function __construct(float $lng,float $lat)
    {
         $this->setLat($lat);
         $this->setLng($lng);
    }

    /**
     * @return float
     */
    public   function getLat(): float
    {
        return  $this->lat;
    }

    /**
     * @param float $lat
     */
    public   function setLat(float $lat): void
    {
         $this->lat = $lat;
    }

    /**
     * @return float
     */
    public   function getLng(): float
    {
        return  $this->lng;
    }

    /**
     * @param float $lng
     */
    public   function setLng(float $lng): void
    {
         $this->lng = $lng;
    }


}
