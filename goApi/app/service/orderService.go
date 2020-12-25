package service

import (
	"goApi/util/helper"
	"net/http"
)

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
