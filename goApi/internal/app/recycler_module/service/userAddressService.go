package service

import (
	userPld "goApi/internal/app/user_module/payload/user"
	"goApi/internal/repository"
	"goApi/pkg/enum"
	"goApi/pkg/util/helper"
)

/*func init()  {
	userAddrRepo
}*/

//用户信息
func AddrList(userId int64) helper.Response {
	var dataMap = make(map[string]interface{}, 2)
	addrList, err := repository.UserAddressRepo.AddressList(userId)
	//todo
	if err != nil {
		resp := helper.RespError(helper.GetUsrAErrMsg(enum.ProcessServiceMsg, enum.BusinessUserAddressMsg, enum.SpecificErrorFindMsg),
			helper.GetUsrAErrCode(enum.ProcessServiceCode, enum.BusinessUserAddressCode, enum.SpecificErrorFindCode), userId)
		return resp
	}
	dataMap["addressList"] = addrList
	resp := helper.RespSuccess("获取用户地址列表成功", dataMap)
	return resp
}

func Save(userId int64, userAddrAddPld userPld.UAddressAdd) helper.Response {
	var dataMap = make(map[string]interface{}, 2)
	uAddrModel := repository.UserAddressRepo.BuildByPayload(userAddrAddPld, userId)
	uAddrModel, effectRow := repository.UserAddressRepo.Save(uAddrModel)
	if effectRow <= 1 && userAddrAddPld.Id > 0 {
		resp := helper.RespError(helper.GetUsrAErrMsg(enum.ProcessServiceMsg, enum.BusinessUserAddressMsg, enum.SpecificErrorInsertMsg),
			helper.GetUsrAErrCode(enum.ProcessServiceCode, enum.BusinessUserAddressCode, enum.SpecificErrorInsertCode), dataMap)
		return resp
	}
	dataMap["address"] = uAddrModel
	if userAddrAddPld.Id > 0 {
		return helper.RespSuccess("修改地址数据成功", uAddrModel)
	} else {
		return helper.RespSuccess("新增地址数据成功", uAddrModel)
	}
}

func AddrFind(addrId, userId int64) helper.Response {
	var dataMap = make(map[string]interface{}, 2)
	userAddress, err := repository.UserAddressRepo.Find(addrId, userId)
	if err != nil {
		resp := helper.RespError(helper.GetUsrAErrMsg(enum.ProcessServiceMsg, enum.BusinessUserAddressMsg, enum.SpecificErrorFindMsg),
			helper.GetUsrAErrCode(enum.ProcessServiceCode, enum.BusinessUserAddressCode, enum.SpecificErrorFindCode), dataMap)
		return resp
	}
	dataMap["address"] = userAddress
	resp := helper.RespSuccess("获取用户地址成功", dataMap)
	return resp
}

func AddrDel(addrId, userId int64) helper.Response {
	var dataMap = make(map[string]interface{}, 2)
	userAddress, err := repository.UserAddressRepo.Find(addrId, userId)
	if err != nil {
		resp := helper.RespError(helper.GetUsrAErrMsg(enum.ProcessServiceMsg, enum.BusinessUserAddressMsg, enum.SpecificErrorFindMsg),
			helper.GetUsrAErrCode(enum.ProcessServiceCode, enum.BusinessUserAddressCode, enum.SpecificErrorFindCode), dataMap)
		return resp
	}
	if userAddress.ID <= 0 {
		resp := helper.RespError(helper.GetUsrAErrMsg(enum.ProcessRepositoryMsg, enum.BusinessUserAddressMsg, enum.SpecificErrorDataNotFoundMsg),
			helper.GetUsrAErrCode(enum.ProcessRepositoryCode, enum.BusinessUserAddressCode, enum.SpecificErrorDataNotFoundCode), dataMap)
		return resp
	}
	rowsAffect, err := repository.UserAddressRepo.Del(addrId, userId)
	if err != nil || rowsAffect <= 0 {
		resp := helper.RespError(helper.GetUsrAErrMsg(enum.ProcessServiceMsg, enum.BusinessUserAddressMsg, enum.SpecificErrorDeleteMsg),
			helper.GetUsrAErrCode(enum.ProcessServiceCode, enum.BusinessUserAddressCode, enum.SpecificErrorDeleteCode), dataMap)
		return resp
	}
	dataMap["address"] = userAddress
	resp := helper.RespSuccess("删除用户地址成功", dataMap)
	return resp
}
