package router

import (
	"github.com/gin-gonic/gin"
	. "goApi/internal/app/file_server/api"
	"goApi/internal/middleware"
)

var routerFileServer *gin.Engine

func InitFileServerRouter() *gin.Engine {
	router = gin.Default()
	router.Use(middleware.Cors())

	// 路由组:  鉴权 模块
	authGroup := router.Group("/auth")
	{
		authGroup.POST("/login", AuthServer.Login)            //登录 返回json web token
		authGroup.POST("/info", AuthServer.Info)              //返回 用户的信息
		authGroup.POST("/trans/token", AuthServer.TransToken) //返回 交换将要过期的token
	}

	// 路由组:  upload 模块
	uploadGroup := router.Group("/upload")
	{
		uploadGroup.POST("/single", UploadServer.Single) //单文件上传
		uploadGroup.POST("/multi", UploadServer.Multi)   //多文件上传
	}

	return routerFileServer
}
