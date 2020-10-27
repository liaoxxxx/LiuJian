package router

import (
	"github.com/gin-gonic/gin"
	. "goApi/apis"
)

func InitRouter() *gin.Engine {
	router := gin.Default()

	router.GET("/users", Users)

	router.POST("/user", Store)

	router.PUT("/user/:id", Update)

	router.DELETE("/user/:id", Destroy)
	//##################

	router.GET("/user/login/:phone/:password", Login)
	return router
}
