package router

import (
	"github.com/gin-gonic/gin"
	"goApi/internal/app/websocket/api/recycler"
	"goApi/internal/middleware"
)

var routerWs *gin.Engine

func InitWsRouter() *gin.Engine {

	routerWs := gin.New()

	/*	userGroup:=routerWs.Group("/user")
		//  之后使用中间件
		userGroup.Use(middleware.UserAuth())
		{
			userGroup.POST("/statInfo", GetStateInfo)
		}*/

	recyclerGroup := routerWs.Group("/recycler")
	//  之后使用中间件
	recyclerGroup.Use(middleware.RecyclerAuth())
	{
		recyclerGroup.GET("/order/distribute", recycler.OrderServer.OrderDistribute)
	}

	return routerWs
}
