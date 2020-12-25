package user

import (
	model "goApi/app/models"
	"goApi/util/helper"
	"net/http"
)

//用户信息
func getAllAddress(uid int64) *helper.Response {
	var resp = new(helper.Response)
	var userAddr model.UserAddress
	dataMap := make(map[string]interface{}, 2)

	userOne, err := userAddr.List(uid)

	if err != nil {
		resp.Code = http.StatusBadRequest
		resp.Msg = "未注册的用户"

		return resp
	} else {

		dataMap["user"] = userOne
		resp.Code = http.StatusOK
		resp.Msg = "获取用户成功"
		resp.Data = dataMap

		return resp
	}
}
