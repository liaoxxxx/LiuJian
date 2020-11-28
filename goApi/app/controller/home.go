package controller

import (
	"github.com/gin-gonic/gin"
)

//列表数据
func Index(c *gin.Context) {
	c.JSON(200, gin.H{
		"html": "<b>Hello, world!</b>",
	})
}

//
func Skeleton() {
	//轮播图

	//用户统计数据

	//回收种类

	//滚动通知

	//当前城市

}
