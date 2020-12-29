package router

import (
	"github.com/gin-gonic/gin"
	. "goApi/app/controller"
	orderModule "goApi/app/controller/order"
	userAddrModule "goApi/app/controller/user"
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
		userGroup.POST("/login", userAddrModule.Login)
	}
	//  之后使用中间件
	userGroup.Use(middleware.UserAuth(), middleware.Cors())
	{
		userGroup.POST("/statInfo", userAddrModule.GetStateInfo)
		userGroup.POST("/userInfo", userAddrModule.UserInfo)
	}
	//
	userAddrGroup := router.Group("/userAddr")
	userAddrGroup.Use(middleware.UserAuth())
	{
		userAddrGroup.POST("/list", userAddrModule.AddrList)
	}

	//################################################
	//  homeGroup 模块
	homeGroup := router.Group("/home")
	{
		homeGroup.POST("/skeleton", Skeleton)
	}

	//################################################
	//  order 模块
	orderGroup := router.Group("/order")
	{
		orderGroup.POST("/create", orderModule.Create)
		orderGroup.POST("/confirm", orderModule.Confirm)
		orderGroup.POST("/addPageSkeleton", orderModule.AddSkeleton)
	}

	return router
}
