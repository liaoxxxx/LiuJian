package router

import (
	"github.com/gin-gonic/gin"
	. "goApi/internal/app/user_module/api"
	"goApi/internal/middleware"
)

var router *gin.Engine

func InitUserRouter() *gin.Engine {
	router = gin.Default()
	router.Use(middleware.Cors())
	router.GET("/", Index)

	// 路由组:  user 模块
	userGroup := router.Group("/user")
	{
		userGroup.POST("/login", Login)
	}
	//  之后使用中间件
	userGroup.Use(middleware.UserAuth(), middleware.Cors())
	{
		userGroup.POST("/statInfo", GetStateInfo)
		userGroup.POST("/userInfo", UserInfo)
		userGroup.POST("/userCenter", UserCenter)
	}
	//
	userAddrGroup := router.Group("/userAddr")
	userAddrGroup.Use(middleware.UserAuth())
	{
		userAddrGroup.GET("/find", AddrFind)
		userAddrGroup.GET("/list", AddrList)
		userAddrGroup.POST("/save", AddrSave)
		userAddrGroup.GET("/del", AddrDel)
	}

	//################################################
	//  homeGroup 模块
	homeGroup := router.Group("/home")
	{
		homeGroup.POST("/skeleton", Skeleton)
	}

	//################################################
	//  order 模块
	orderGroup := router.Group("/order", middleware.UserAuth())
	{
		orderGroup.POST("/create", Create)
		orderGroup.POST("/confirm", Confirm)
		orderGroup.POST("/addSkeleton", AddSkeleton)
		orderGroup.GET("/list", List)
		orderGroup.GET("/detail", Detail)
	}

	return router
}
