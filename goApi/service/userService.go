package service

import (
	"github.com/gin-gonic/gin"
	model "goApi/models"
	"goApi/util/helper"
	"net/http"
	"strings"
)

func Login(phone string, password string) {
	var user model.User
	userOne, res := user.FindByPhone(phone)

	if res.RowsAffected < 1 {
		c.JSON(http.StatusOK, gin.H{
			"code": http.StatusOK,
			"msg":  "未注册的用户",
			"user": userOne,
		})
		return
	}
	//md5 加密后的密码
	pwdMd5 := userOne.GetMd5Pwd(password, userOne.Salt)
	if strings.Compare(pwdMd5, userOne.Password) == 0 {
		jwtTool := helper.NewJWT()
		userClaims := helper.CustomClaims{ID: userOne.ID, Phone: userOne.Phone, Name: userOne.Username}
		token, _ := jwtTool.CreateToken(userClaims)

		c.JSON(http.StatusOK, gin.H{
			"code":  http.StatusOK,
			"msg":   "登陆成功",
			"token": token,
		})
	} else {

		c.JSON(http.StatusOK, gin.H{
			"code": http.StatusOK,
			"msg":  "密码错误",
		})
	}
}
