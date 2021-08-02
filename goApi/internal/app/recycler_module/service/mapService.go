package service

import (
	pLd "goApi/internal/app/recycler_module/payload"
	"goApi/pkg/enum"
	"goApi/pkg/util/amap"
	"goApi/pkg/util/helper"
)

type mapService struct {
}

var MapService mapService

//用户登录
func (*mapService) PathPlanning(pathPlanPld pLd.PathPlanning) helper.ServiceResp {
	var resp helper.ServiceResp
	resp.Code = enum.DefaultSuccessCode
	start := amap.Location{
		Lat: pathPlanPld.Start.Latitude,
		Lng: pathPlanPld.Start.Longitude,
	}
	To := amap.Location{
		Lat: pathPlanPld.To.Latitude,
		Lng: pathPlanPld.To.Longitude,
	}
	amap.AmapTool.DirectionPlanningElectroBike(start, To)

	return resp

}
