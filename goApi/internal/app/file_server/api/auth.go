package api

import (
	"github.com/gin-gonic/gin"
	"goApi/internal/app/file_server/payload"
	"goApi/pkg/util/helper"
)

//
//  authServer | 文件服务鉴权
//  @Description:
//
type authServer struct {
}

var AuthServer authServer

// Login
//  @Description: 登录
//  @receiver authServer
//  @param ctx
//
func (authServer) Login(ctx *gin.Context) {
	//绑定payload的 json
	loginPld := payload.AppKeyLogin{}
	err := helper.BindJson(ctx, loginPld)
	if err != nil {
		return
	}

}

// Info
//  @Description:  返回用户的信息
//  @receiver authServer
//  @param ctx
//
func (authServer) Info(ctx *gin.Context) {
	//绑定payload的 json

}

// TransToken
//  @Description:  交换将要过期的token
//  @receiver authServer
//  @param ctx
//
func (authServer) TransToken(ctx *gin.Context) {
	//绑定payload的 json

}
