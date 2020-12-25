package order

import (
	"github.com/gin-gonic/gin"
	orderService "goApi/app/service/order"
	"net/http"
)

//列表数据
func Confirm(c *gin.Context) {
	c.JSON(200, gin.H{
		"html": "<b>Hello, world!</b>",
	})
}

//列表数据
func Create(c *gin.Context) {

	c.JSON(http.StatusOK, orderService.Create())
}

//列表数据
func AddSkeleton(c *gin.Context) {
	c.JSON(http.StatusOK, orderService.AddSkeleton())
}
