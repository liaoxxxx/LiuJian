package service

import (
	"goApi/internal/models/entity"
	"goApi/pkg/enum"
	"goApi/pkg/util/helper"
)

//获取移动端 首页数据
func GetHomeMobileData(uid int64) (resp helper.ServiceResp) {
	var sysGroup entity.SystemGroup

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
	dataMap["UserStatInfo"] = entity.UserStatInfo{}

	//其他功能
	anotherOption, _ := sysGroup.GetDataByConfigName("user_client_home_another_option")
	dataMap["AnotherOption"] = anotherOption

	///

	resp.Message = "success"
	resp.Code = enum.DefaultSuccessCode
	resp.Data = dataMap
	return resp

}
