package router

import (
	"github.com/gin-gonic/gin"
	. "goApi/apis"
	"goApi/midleware"
)

var router *gin.Engine

func InitRouter() *gin.Engine {
	router = gin.Default()

	router.GET("/", Index)

	// 简单的路由组:  user 模块
	userGroup := router.Group("/user", midleware.UserAuth())
	{
		userGroup.POST("/login", Login)
		userGroup.POST("/userInfo", UserInfo)
	}

	return router
}
