package service

import (
	"fmt"
	model "goApi/app/models"
	"goApi/util/helper"
	"net/http"
)

//获取移动端 首页数据
func GetHomeMobileData(uid int64) *helper.Response {
	var resp = new(helper.Response)
	var sysGroupData model.SystemGroupData
	//var userModel model.User
	var bannerGroupId = 48
	dataMap := make(map[string]interface{}, 5)
	//轮播图
	fmt.Println("--------------------")
	fmt.Println(bannerGroupId)

	bannerList, _ := sysGroupData.GetValueList(int64(bannerGroupId))
	dataMap["BannerList"] = bannerList
	fmt.Println(bannerList)
	//回收种类

	//滚动通知

	//当前城市

	resp.Code = http.StatusBadRequest
	resp.Data = dataMap
	return resp

}
