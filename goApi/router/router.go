package router

import (
	"github.com/gin-gonic/gin"
	. "goApi/apis"
)
var router *gin.Engine

func InitRouter() *gin.Engine {
	router = gin.Default()


	router.GET("/", Index)
	router.GET("/users", Users)

	router.POST("/user", Store)

	router.PUT("/user/:id", Update)

	router.DELETE("/user/:id", Destroy)
	//##################

	router.GET("/user/login/:phone/:password", Login)
	return router
}
