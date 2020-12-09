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
	var groupId = 88
	resp := homeService.GetHomeMobileData(int64(groupId))
	c.JSON(http.StatusOK, resp)
}
