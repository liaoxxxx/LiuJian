package user

import (
	"goApi/app/repository"
	"goApi/util/helper"
)

//用户信息
func AddrList(userId int64) helper.Response {
	var userAddrRepo repository.UserAddressRepo
	var dataMap = make(map[string]interface{},2)
	var resp helper.Response

	addrList,err:=userAddrRepo.AddressList(userId)
	//todo
	if err !=nil{
		resp=helper.RespError("111",222,userId)
	}
	dataMap["addressList"]=addrList
	resp= helper.RespSuccess("222",dataMap)
	return resp
}
