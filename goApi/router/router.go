package router

import (
	"github.com/gin-gonic/gin"
	. "goApi/app/controller"
	orderModule "goApi/app/controller/order"
	"goApi/app/middleware"
)

var router *gin.Engine

func InitRouter() *gin.Engine {
	router = gin.Default()
	router.Use(middleware.Cors())
	router.GET("/", Index)

	// 路由组:  user 模块
	userGroup := router.Group("/user")
	{
		userGroup.POST("/login", Login)
	}
	//  之后使用中间件
	userGroup.Use(middleware.UserAuth(),middleware.Cors())
	{
		userGroup.POST("/statInfo", GetStateInfo)
		userGroup.POST("/userInfo", UserInfo)
	}

	//################################################
	//  homeGroup 模块
	homeGroup := router.Group("/home",middleware.Cors())
	{
		homeGroup.POST("/skeleton", Skeleton)
	}

	//################################################
	//  homeGroup 模块
	orderGroup := router.Group("/order",middleware.Cors())
	{
		orderGroup.POST("/create", orderModule.Create)
		orderGroup.POST("/confirm", orderModule.Confirm)
		orderGroup.POST("/addPageSkeleton", orderModule.AddSkeleton)
	}

	return router
}
