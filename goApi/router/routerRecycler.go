package router

import (
	"github.com/gin-gonic/gin"
	recModule "goApi/internal/app/recycler_module/api"
	"goApi/internal/middleware"
)

var routerREC *gin.Engine

func InitRecRouter() *gin.Engine {
	routerREC = gin.Default()
	routerREC.Use(middleware.Cors())
	//routerREC.GET("/", Index)

	// 路由组:  user 模块
	userGroup := routerREC.Group("/recycler")
	{
		userGroup.POST("/login", recModule.RecyclerServer.Login)
		userGroup.POST("/pwd_login", recModule.RecyclerServer.PwdLogin)
	}
	//  之后使用中间件
	userGroup.Use(middleware.RecyclerAuth(), middleware.Cors())
	{
		userGroup.POST("/workStat", recModule.GetStateInfo)
		userGroup.POST("/userInfo", recModule.UserInfo)
		userGroup.POST("/userCenter", recModule.UserCenter)
	}

	orderGroup := routerREC.Group("/order")
	orderGroup.Use(middleware.RecyclerAuth())
	{
		//orderGroup.GET("/find", userModule.AddrFind)
		orderGroup.GET("/list", recModule.OrderServer.List)
		//orderGroup.POST("/save", userModule.AddrSave)
		//orderGroup.GET("/del", userModule.AddrDel)
	}

	/*
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
	*/
	return routerREC
}
