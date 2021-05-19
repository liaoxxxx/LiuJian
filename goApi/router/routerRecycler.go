package router

import (
	"github.com/gin-gonic/gin"
	. "goApi/internal/app/user_module/api"
	orderModule "goApi/internal/app/user_module/api/order"
	userModule "goApi/internal/app/user_module/api/user"
	"goApi/internal/middleware"
)

var routerREC *gin.Engine

func InitRecRouter() *gin.Engine {
	routerREC = gin.Default()
	routerREC.Use(middleware.Cors())
	routerREC.GET("/", Index)

	// 路由组:  user 模块
	userGroup := routerREC.Group("/user")
	{
		userGroup.POST("/login", userModule.Login)
	}
	//  之后使用中间件
	userGroup.Use(middleware.UserAuth(), middleware.Cors())
	{
		userGroup.POST("/statInfo", userModule.GetStateInfo)
		userGroup.POST("/userInfo", userModule.UserInfo)
		userGroup.POST("/userCenter", userModule.UserCenter)
	}
	//
	userAddrGroup := routerREC.Group("/userAddr")
	userAddrGroup.Use(middleware.UserAuth())
	{
		userAddrGroup.GET("/find", userModule.AddrFind)
		userAddrGroup.GET("/list", userModule.AddrList)
		userAddrGroup.POST("/save", userModule.AddrSave)
		userAddrGroup.GET("/del", userModule.AddrDel)
	}

	//################################################
	//  homeGroup 模块
	homeGroup := routerREC.Group("/home")
	{
		homeGroup.POST("/skeleton", Skeleton)
	}

	//################################################
	//  order 模块
	orderGroup := routerREC.Group("/order", middleware.UserAuth())
	{
		orderGroup.POST("/create", orderModule.Create)
		orderGroup.POST("/confirm", orderModule.Confirm)
		orderGroup.POST("/addSkeleton", orderModule.AddSkeleton)
		orderGroup.GET("/list", orderModule.List)
		orderGroup.GET("/detail", orderModule.Detail)
	}

	return routerREC
}
