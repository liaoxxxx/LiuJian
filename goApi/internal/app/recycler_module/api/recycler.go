package api

import (
	"github.com/gin-gonic/gin"
	recPLd "goApi/internal/app/recycler_module/payload"
	"goApi/internal/app/recycler_module/service"
	userService "goApi/internal/app/user_module/service/user"
	"goApi/pkg/util/helper"
	"net/http"
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

func UserInfo(c *gin.Context) {
	token := c.GetHeader("token")

	resp := userService.UserInfo(token)
	c.JSON(http.StatusOK, resp)
}

func UserCenter(c *gin.Context) {
	uid, _ := c.Get("uid")
	userId := uid.(int64)
	resp := userService.UCenter(userId)
	c.JSON(http.StatusOK, resp)
}

//删除数据
func GetStateInfo(c *gin.Context) {
	//用户统计数据
	uid, _ := c.Get("uid")
	userId := uid.(int64)
	resp := userService.GetStateInfo(userId)
	c.JSON(http.StatusOK, resp)
}
