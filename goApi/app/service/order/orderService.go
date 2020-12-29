package order

import (
	"github.com/gin-gonic/gin"
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
func Create(c *gin.Context) *helper.Response {
	var resp = new(helper.Response)

	addressId := c.DefaultPostForm("addressId", "0")
	isPreengage := c.DefaultPostForm("addressId", "0")
	mark := c.DefaultPostForm("addressId", "0")
	orderKey := c.DefaultPostForm("addressId", "0")
	phone := c.DefaultPostForm("addressId", "0")
	preengageTime := c.DefaultPostForm("preengageTime", "0")

	if addressId == "0" || isPreengage == "0" || mark == "0" || orderKey == "0" || phone == "0" || preengageTime == "0" {
		resp.ErrMsg = "参数不完整"
		resp.Msg = "error"
		resp.Code = http.StatusOK
		resp.Status = "ok"
		return resp
	}
	///
	resp.ErrCode = 0
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

	resp.ErrCode = 0
	resp.Msg = "success"
	resp.Code = http.StatusOK
	resp.Status = "ok"
	resp.Data = dataMap
	return resp
}
