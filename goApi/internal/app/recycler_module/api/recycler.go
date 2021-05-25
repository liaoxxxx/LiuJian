package api

import (
	"github.com/gin-gonic/gin"
	recPLd "goApi/internal/app/recycler_module/payload"
	"goApi/internal/app/recycler_module/service"
	"goApi/pkg/util/helper"
	"net/http"
	"strconv"
)

type recyclerServer struct{}

var RecyclerServer = new(recyclerServer)

func (*recyclerServer) Login(c *gin.Context) {
	var smsCodeLogin recPLd.SmsCodeLogin
	helper.BindQuery(c, &smsCodeLogin)
	resp := service.RecyclerService.Login(smsCodeLogin)
	c.JSON(http.StatusOK, resp)
}

func (*recyclerServer) PwdLogin(c *gin.Context) {
	var pwdLogin recPLd.PasswordLogin
	helper.BindQuery(c, &pwdLogin)
	resp := service.RecyclerService.PwdLogin(pwdLogin)
	c.JSON(http.StatusOK, resp)
}

func UserInfo(ctx *gin.Context) {

	recId := helper.GetRecIdByCtx(ctx)
	b, resp := service.RecyclerService.UserInfo(recId)
	if b {
		ctx.JSON(http.StatusOK, helper.RespSuccess(resp.Msg, resp.Data))
	} else {
		ctx.JSON(http.StatusOK, helper.RespError(resp.Msg, strconv.Itoa(int(resp.Code)), resp.Data))
	}

}

func UserCenter(ctx *gin.Context) {
	recId := helper.GetRecIdByCtx(ctx)
	resp := service.UCenter(recId)
	ctx.JSON(http.StatusOK, resp)
}

// GetStateInfo 用户统计数据
func GetStateInfo(ctx *gin.Context) {
	recId := helper.GetRecIdByCtx(ctx)
	resp := service.RecyclerService.GetStateInfo(recId)
	ctx.JSON(http.StatusOK, helper.RespSuccess(resp.Msg, resp.Data))
}
