package controller

import (
	"github.com/gin-gonic/gin"
	homeService "goApi/app/service"
	"net/http"
)

//列表数据
func Index(c *gin.Context) {
	c.JSON(200, gin.H{
		"html": "<b>Hello, world!</b>",
	})
}

//
func Skeleton(c *gin.Context) {

	resp := homeService.GetHomeMobileData()
	c.JSON(http.StatusOK, resp)
}
