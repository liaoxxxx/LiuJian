package router

import (
	"github.com/gin-gonic/gin"
	. "goApi/app/controller"
	"goApi/app/midleware"
)

var router *gin.Engine

func InitRouter() *gin.Engine {
	router = gin.Default()

	router.GET("/", Index)

	// 简单的路由组:  user 模块
	userGroup := router.Group("/user")
	{
		userGroup.POST("/login", Login)
	}
	// 中间件
	userGroup.Use(midleware.UserAuth())
	{
		userGroup.POST("/userInfo", UserInfo)
	}

	//################################################
	// 简单的路由组:  user 模块
	homeGroup := router.Group("/home")
	{
		homeGroup.POST("/skeleton", Skeleton)
	}

	return router
}
