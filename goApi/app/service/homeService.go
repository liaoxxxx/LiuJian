package service

import (
	model "goApi/app/models"
	"goApi/util/helper"
	"net/http"
)

//获取移动端 首页数据
func GetHomeMobileData(uid int64) *helper.Response {
	var resp = new(helper.Response)
	var sysGroup model.SystemGroup

	dataMap := make(map[string]interface{}, 5)
	//轮播图
	bannerList, _ := sysGroup.GetDataByConfigName("routine_home_banner")
	dataMap["BannerList"] = bannerList
	//回收种类

	//滚动通知
	rollNotice, _ := sysGroup.GetDataByConfigName("routine_home_roll_news")
	dataMap["RollNoticeList"] = rollNotice
	//当前城市

	resp.Code = http.StatusBadRequest
	resp.Data = dataMap
	return resp

}
