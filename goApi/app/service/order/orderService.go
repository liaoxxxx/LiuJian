package order

import (
	model "goApi/app/models"
	"goApi/util/helper"
	"net/http"
)

//获取移动端 首页数据
func genOrderKey(uid int64) string {
	var key string
	key = ""
	return key
}

//获取移动端 首页数据
func Create() *helper.Response {
	var resp = new(helper.Response)

	///
	resp.ErrMsg = "null"
	resp.Msg = "success"
	resp.Code = http.StatusOK
	resp.Status = "ok"
	return resp
}

//获取移动端 首页数据
func AddSkeleton() *helper.Response {
	var resp = new(helper.Response)
	var sysGroup model.SystemGroup
	var dataMap = make(map[string]interface{}, 3)
	///
	//回收种类
	recycleCate, _ := sysGroup.GetDataByConfigName("user_client_home_recycle_category")
	dataMap["RecycleCate"] = recycleCate

	resp.ErrMsg = "null"
	resp.Msg = "success"
	resp.Code = http.StatusOK
	resp.Status = "ok"
	resp.Data = dataMap
	return resp
}
