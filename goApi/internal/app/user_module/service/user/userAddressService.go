package user

import (
	"fmt"
	userPld "goApi/internal/app/user_module/payload/user"
	"goApi/internal/logic"
	"goApi/internal/repository"
	"goApi/pkg/enum"
	"goApi/pkg/logger"
	"goApi/pkg/util/helper"
)

/*func init()  {
	 repository.UserAddressRepo
}*/

//用户信息
func AddrList(userId int64) (svcResp helper.ServiceResp) {
	var dataMap = make(map[string]interface{}, 2)
	addrList, err := repository.UserAddressRepo.AddressList(userId)
	//todo
	if err != nil {
		svcResp.Message = "获取用户地址列表失败"
		svcResp.Code = enum.DatabaseFindErrMsg
		return svcResp
	}
	dataMap["addressList"] = addrList
	svcResp.Message = "获取用户地址列表失败"
	svcResp.Code = enum.DefaultSuccessCode
	svcResp.Data = dataMap
	logger.Logger.Info(fmt.Sprintf("%v", svcResp.Code))
	return svcResp
}

func Save(userId int64, userAddrAddPld userPld.UAddressAdd) (svcResp helper.ServiceResp) {
	var dataMap = make(map[string]interface{}, 2)
	uAddrModel := logic.UserAddressLogic.BuildByPayload(userAddrAddPld, userId)
	if uAddrModel.Longitude == "" || uAddrModel.Latitude == "" {
		err := logic.UserAddressLogic.LocationEmptyHandle(&uAddrModel)
		if err != nil {
			logger.Logger.Info(err.Error())
			svcResp.Code = enum.DefaultRequestErrCode
			svcResp.Message = enum.DefaultRequestErrMsg
			return svcResp
		}
	}
	uAddrModel, effectRow := repository.UserAddressRepo.Save(uAddrModel)
	if effectRow < 1 && userAddrAddPld.Id == 0 {
		svcResp.Code = enum.DatabaseUpdateErrCode
		svcResp.Message = enum.DatabaseUpdateErrMsg
		return svcResp
	}
	dataMap["address"] = uAddrModel
	svcResp.Data = dataMap
	if userAddrAddPld.Id > 0 {
		svcResp.Code = enum.DefaultSuccessCode
		svcResp.Message = enum.DbSaveScsMsg
	}
	return svcResp
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
