package service

import (
	"fmt"
	pLd "goApi/internal/app/recycler_module/payload"
	"goApi/internal/enum"
	pkgEnum "goApi/pkg/enum"
	"goApi/pkg/logger"
	"strings"

	"goApi/internal/models/entity"
	"goApi/internal/repository"
	"goApi/pkg/util/helper"
	"net/http"
)

type recyclerService struct {
}

var RecyclerService recyclerService

//用户登录
func (*recyclerService) Login(smsCodePld pLd.SmsCodeLogin) helper.ServiceResp {
	var resp helper.ServiceResp
	if smsCodePld.Phone == "" || smsCodePld.SmsCode == "" {
		resp.Message = helper.GetErrMsg(enum.AppRecycleManMsg, enum.ProcessServiceMsg, enum.BusinessUserMsg, enum.SpecificErrorParamUndefinedMsg)
		resp.Code = helper.GetErrCode(enum.AppRecycleManCode, enum.ProcessServiceCode, enum.BusinessUserCode, enum.SpecificErrorParamUndefinedCode)
		resp.Data = smsCodePld
		return resp
	}

	recycler, _ := repository.RecyclerRepo.FindByPhone(smsCodePld.Phone)
	if recycler.ID < 1 {
		resp.Code = pkgEnum.PhoneNotRegisterErrCode
		resp.Message = pkgEnum.PhoneNotRegisterErrMsg
		return resp
	}
	//md5 加密后的密码
	//pwdMd5 := helper.GetMd5Pwd(smsCodePld.Password, recycler.Salt)
	//fmt.Println(pwdMd5)
	//fmt.Println(recycler.Password)
	//if strings.Compare(pwdMd5, recycler.Password) == 0 {
	jwtTool := helper.NewJWT()
	userClaims := helper.CustomClaims{ID: recycler.ID, Phone: recycler.Phone, Name: recycler.Nickname}
	token, _ := jwtTool.CreateToken(userClaims)

	resp.Code = pkgEnum.DefaultSuccessCode
	resp.Message = "登陆成功"
	resp.Data = map[string]string{
		"token": token,
	}
	return resp
	//} else {
	//	resp.Code = http.StatusBadRequest
	//	resp.Msg = "密码错误"
	//	return resp
	//}

}

//用户登录
func (*recyclerService) PwdLogin(pwdPld pLd.PasswordLogin) helper.ServiceResp {
	var resp helper.ServiceResp
	if pwdPld.Phone == "" || pwdPld.Password == "" {
		resp.Message = helper.GetErrMsg(enum.AppRecycleManMsg, enum.ProcessServiceMsg, enum.BusinessUserMsg, enum.SpecificErrorParamUndefinedMsg)
		resp.Code = helper.GetErrCode(enum.AppRecycleManCode, enum.ProcessServiceCode, enum.BusinessUserCode, enum.SpecificErrorParamUndefinedCode)
		resp.Data = pwdPld
		return resp
	}

	recycler, _ := repository.RecyclerRepo.FindByPhone(pwdPld.Phone)
	if recycler.ID < 1 {
		resp.Code = pkgEnum.PhoneNotRegisterErrCode
		resp.Message = pkgEnum.PhoneNotRegisterErrMsg
		return resp
	}
	//md5 加密后的密码
	pwdMd5 := helper.GetMd5Pwd(pwdPld.Password, recycler.Salt)

	fmt.Println("-------------  GetMd5Pwd -------------")
	fmt.Println(recycler)
	fmt.Println(pwdMd5)
	fmt.Println(recycler.Pwd)
	fmt.Println(recycler.Salt)
	if strings.Compare(pwdMd5, recycler.Pwd) == 0 {
		jwtTool := helper.NewJWT()
		userClaims := helper.CustomClaims{ID: recycler.ID, Phone: recycler.Phone, Name: recycler.Nickname}
		token, _ := jwtTool.CreateToken(userClaims)

		resp.Code = pkgEnum.DefaultSuccessCode
		resp.Message = "登陆成功"
		resp.Data = map[string]string{
			"token": token,
		}
		return resp
	} else {
		resp.Code = pkgEnum.PasswordAuthErrCode
		resp.Message = pkgEnum.PasswordAuthErrMsg
		return resp
	}

}

//用户信息
func (*recyclerService) UserInfo(recId int64) (bool, helper.Response) {
	var resp helper.Response
	var recycler entity.Recycler
	dataMap := make(map[string]interface{}, 2)
	recycler, err := repository.RecyclerRepo.FindByUid(recId)

	if err != nil {
		logger.Logger.Info("--------未注册的用户" + err.Error())
		resp.Code = pkgEnum.DatabaseFindErrCode
		resp.Msg = pkgEnum.DatabaseFindErrMsg

		return false, resp
	} else {
		logger.Logger.Info("--------获取用户成功")
		dataMap["recyclerInfo"] = recycler
		resp.Code = http.StatusOK
		resp.Msg = "获取用户成功"
		resp.Data = dataMap

		return true, resp
	}
}

func UCenter(userId int64) helper.Response {
	var resp helper.Response
	//var userRepo repository.UserRepo
	//userRepo.

	dataMap := make(map[string]interface{}, 2)

	resp.Data = dataMap
	return resp
}

func (recyclerService) GetStateInfo(uid int64) *helper.Response {

	var userModel entity.User
	var resp = new(helper.Response)

	userStatInfo, _ := userModel.GetStateInfo(uid)
	dataMap := make(map[string]interface{}, 2)
	dataMap["UserStatInfo"] = userStatInfo
	resp.Data = dataMap
	return resp

}
