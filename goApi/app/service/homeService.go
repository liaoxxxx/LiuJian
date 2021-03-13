package service

import (
	"goApi/app/models/database"
	"goApi/util/helper"
	"net/http"
)

//获取移动端 首页数据
func GetHomeMobileData(uid int64) *helper.Response {
	var resp = new(helper.Response)
	var sysGroup database.SystemGroup

	dataMap := make(map[string]interface{}, 5)
	//轮播图
	bannerList, _ := sysGroup.GetDataByConfigName("routine_home_banner")
	dataMap["BannerList"] = bannerList
	//回收种类
	recycleCate, _ := sysGroup.GetDataByConfigName("user_client_home_recycle_category")
	dataMap["RecycleCate"] = recycleCate
	//滚动通知
	rollNotice, _ := sysGroup.GetDataByConfigName("routine_home_roll_news")
	dataMap["RollNoticeList"] = rollNotice
	//当前城市

	//未登录的用户统计数据
	//rollNotice, _ := sysGroup.GetDataByConfigName("routine_home_roll_news")
	dataMap["UserStatInfo"] = database.UserStatInfo{}

	//其他功能
	anotherOption, _ := sysGroup.GetDataByConfigName("user_client_home_another_option")
	dataMap["AnotherOption"] = anotherOption

	///
	resp.ErrCode = 0
	resp.Msg = "success"
	resp.Code = http.StatusOK
	resp.Data = dataMap
	resp.Status = "ok"
	return resp

}
