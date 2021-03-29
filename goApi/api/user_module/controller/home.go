package controller

import (
	"fmt"
	"github.com/gin-gonic/gin"
	homeService "goApi/api/user_module/service"
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
	uid, _ := c.Get("uid")
	fmt.Println(uid)
	resp := homeService.GetHomeMobileData(2)
	c.JSON(http.StatusOK, resp)
}
