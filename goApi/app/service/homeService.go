package service

import (
	model "goApi/app/models"
	"goApi/util/helper"
	"net/http"
)

//用户登录
func GetHomeMobileData(gid int64) *helper.Response {
	var resp = new(helper.Response)
	var sysGroup model.SystemGroup
	//轮播图
	bannerList, _ := sysGroup.GetField(gid)

	//用户统计数据

	//回收种类

	//滚动通知

	//当前城市
	dataMap := make(map[string]interface{}, 2)
	dataMap["BannerList"] = bannerList

	resp.Code = http.StatusBadRequest

	return resp

}
