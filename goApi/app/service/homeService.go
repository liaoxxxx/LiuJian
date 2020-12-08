package service

import (
	"fmt"
	model "goApi/app/models"
	"goApi/util/helper"
	"net/http"
)

//获取移动端 首页数据
func GetHomeMobileData(gid int64) *helper.Response {
	var resp = new(helper.Response)
	var sysGroup model.SystemGroup
	//轮播图
	fmt.Println("--------------------")
	fmt.Println(gid)
	bannerList, _ := sysGroup.GetField(gid)
	fmt.Println(bannerList)
	//用户统计数据

	//回收种类

	//滚动通知

	//当前城市
	dataMap := make(map[string]interface{}, 2)
	dataMap["BannerList"] = bannerList

	resp.Code = http.StatusBadRequest
	resp.Data = dataMap
	return resp

}
