package user

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
	if phone == "" || password == "" {

		resp.Code = http.StatusOK
		resp.Status = "error"
		resp.Msg = "请输入手机号和密码"

		return resp
	}

	var user model.User
	userOne, err := user.FindByPhone(phone)
	fmt.Println("fmt.Println(userOne)-------------------")
	fmt.Println(userOne.ID)
	fmt.Println(err)
	if userOne.ID < 1 {

		resp.Code = http.StatusBadRequest
		resp.Msg = "未注册的用户"

		return resp
	}
	//md5 加密后的密码
	pwdMd5 := GetMd5Pwd(password, userOne.Salt)
	fmt.Println(pwdMd5)
	fmt.Println(userOne.Password)
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

	userOne, err := user.Find(userClaims.ID)

	if err != nil {
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
	return helper.MD5(password + salt)
}
func GetStateInfo(uid int64) *helper.Response {

	var userModel model.User
	var resp = new(helper.Response)

	userStatInfo, _ := userModel.GetStateInfo(uid)
	dataMap := make(map[string]interface{}, 2)
	dataMap["UserStatInfo"] = userStatInfo
	resp.Data = dataMap
	return resp

}