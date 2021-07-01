package amap

import (
	"encoding/json"
	"fmt"
	"goApi/configs"
	"goApi/pkg/logger"
	"goApi/pkg/util"
	"strconv"
	"strings"
)

type amapTool struct {
}

const (
	geoCodeUrl = "https://restapi.amap.com/v3/geocode/geo"
)

var AmapTool amapTool

func (at *amapTool) GeoCode(address, city string) (geoCodeResp GeocodeResp, err error) {
	params := map[string]string{
		"address": address,
	}
	if city != "" {
		params["city"] = city
	}
	params["key"] = configs.AmapAppKey
	resp, err := util.HttpClient.Get(geoCodeUrl, params, map[string]string{})
	if err != nil {
		return GeocodeResp{}, fmt.Errorf("获取位置信息错误数据:%v", err)
	}
	logger.Logger.Info(fmt.Sprintf("%v", resp))
	err = json.Unmarshal(resp.Body(), &geoCodeResp)
	if err != nil {
		return GeocodeResp{}, fmt.Errorf("位置信息数据解析错误")
	}
	return geoCodeResp, nil
}

func (at *amapTool) GetLocation(geoCodeResp GeocodeResp) (location Location, err error) {
	locationStr := geoCodeResp.GeoCodes[0].Location
	locationArr := strings.Split(locationStr, ",")

	location.Lng, err = strconv.ParseFloat(locationArr[0], 64)
	location.Lat, err = strconv.ParseFloat(locationArr[1], 64)
	return location, nil
}

type Location struct {
	Lng float64
	Lat float64
}
