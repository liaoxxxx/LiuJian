package controller

import (
	"github.com/gin-gonic/gin"
	model "goApi/app/models"
	"net/http"
)

//列表数据
func AddrList(c *gin.Context) {
	var user model.User
	user.Username = c.Request.FormValue("username")
	user.Password = c.Request.FormValue("password")
	result, err := user.Users()

	if err != nil {
		c.JSON(http.StatusOK, gin.H{
			"code":    -1,
			"message": "抱歉未找到相关信息",
		})
		return
	}

	c.JSON(http.StatusOK, gin.H{
		"code": 1,
		"data": result,
	})
}
