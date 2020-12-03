package service

import (
	"fmt"
	model "goApi/app/models"
	"goApi/util/helper"
	"net/http"
	"strings"
)

//用户登录
func Login(phone string, password string) *helper.Response {
	var resp = new(helper.Response)
	var user model.User
	userOne, res := user.FindByPhone(phone)

	if res.RowsAffected < 1 {

		resp.Code = http.StatusBadRequest
		resp.Msg = "未注册的用户"

		return resp
	}
	//md5 加密后的密码
	pwdMd5 := GetMd5Pwd(password, userOne.Salt)
	if strings.Compare(pwdMd5, userOne.Password) == 0 {
		jwtTool := helper.NewJWT()
		userClaims := helper.CustomClaims{ID: userOne.ID, Phone: userOne.Phone, Name: userOne.Username}
		token, _ := jwtTool.CreateToken(userClaims)

		resp.Code = http.StatusOK
		resp.Msg = "登陆成功"
		resp.Data = map[string]string{
			"token": token,
		}
		return resp
	} else {
		resp.Code = http.StatusBadRequest
		resp.Msg = "密码错误"
		return resp
	}

}

//用户信息
func UserInfo(token string) *helper.Response {
	var resp = new(helper.Response)
	var user model.User
	dataMap := make(map[string]interface{}, 2)
	jwtTool := helper.NewJWT()
	userClaims, _ := jwtTool.ParseToken(token)
	fmt.Println("++++++++++++++++++++++++++++++")
	fmt.Println(token)
	fmt.Println(userClaims)
	fmt.Println(userClaims.ID)
	fmt.Println("++++++++++++++++++++++++++++++")

	userOne, res := user.Find(userClaims.ID)

	if res.RowsAffected < 1 {
		resp.Code = http.StatusBadRequest
		resp.Msg = "未注册的用户"

		return resp
	} else {
		userOne.Password = "不给看"
		userOne.Salt = "不给看"

		dataMap["user"] = userOne
		resp.Code = http.StatusOK
		resp.Msg = "获取用户成功"
		resp.Data = dataMap

		return resp
	}
}

/**
获取md5加密后的密码
*/
func GetMd5Pwd(password string, salt string) (md5Pwd string) {
	fmt.Println(password + salt)
	return helper.MD5(password + salt)
}
