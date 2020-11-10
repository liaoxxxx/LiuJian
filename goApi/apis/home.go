package apis

import (
	"github.com/gin-gonic/gin"
)

//列表数据
func Index(c *gin.Context) {
	c.JSON(200, gin.H{
		"html": "<b>Hello, world!</b>",
	})
}
