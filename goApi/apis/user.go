package apis

import (
	"fmt"
	"github.com/gin-gonic/gin"
	model "goApi/models"
	"goApi/util/helper"
	"net/http"
	"strconv"
	"strings"
)

//列表数据
func Users(c *gin.Context) {
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

//添加数据
func Store(c *gin.Context) {
	var user model.User
	user.Username = c.Request.FormValue("username")
	user.Password = c.Request.FormValue("password")
	id, err := user.Insert()

	if err != nil {
		c.JSON(http.StatusOK, gin.H{
			"code":    -1,
			"message": "添加失败",
		})
		return
	}
	c.JSON(http.StatusOK, gin.H{
		"code":    1,
		"message": "添加成功",
		"data":    id,
	})
}

//修改数据
func Update(c *gin.Context) {
	var user model.User
	id, err := strconv.ParseInt(c.Param("id"), 10, 64)
	user.Password = c.Request.FormValue("password")
	result, err := user.Update(id)
	if err != nil || result.ID == 0 {
		c.JSON(http.StatusOK, gin.H{
			"code":    -1,
			"message": "修改失败",
		})
		return
	}
	c.JSON(http.StatusOK, gin.H{
		"code":    1,
		"message": "修改成功",
	})
}

//删除数据
func Destroy(c *gin.Context) {
	var user model.User
	id, err := strconv.ParseInt(c.Param("id"), 10, 64)
	result, err := user.Destroy(id)
	if err != nil || result.ID == 0 {
		c.JSON(http.StatusOK, gin.H{
			"code":    -1,
			"message": "删除失败",
		})
		return
	}
	c.JSON(http.StatusOK, gin.H{
		"code":    1,
		"message": "删除成功",
	})
}

func Login(c *gin.Context) {
	var user model.User
	phone := c.PostForm("phone")
	password := c.PostForm("password")
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

func UserInfo(c *gin.Context) {
	var user model.User
	phone := c.Param("phone")
	token := c.GetHeader("token")

	jwtTool := helper.NewJWT()
	userClaims, _ := jwtTool.ParseToken(token)
	fmt.Println("++++++++++++++++++++++++++++++")
	fmt.Println(token)
	fmt.Println(userClaims)
	fmt.Println(userClaims.ID)
	fmt.Println("++++++++++++++++++++++++++++++")

	userOne, res := user.FindByPhone(phone)

	if res.RowsAffected < 1 {
		c.JSON(http.StatusOK, gin.H{
			"code": http.StatusOK,
			"msg":  "未注册的用户",
			"user": userOne,
		})
		return
	} else {
		c.JSON(http.StatusOK, gin.H{
			"code": http.StatusOK,
			"msg":  "用户的Claims",
			"user": userClaims,
		})
	}
}
