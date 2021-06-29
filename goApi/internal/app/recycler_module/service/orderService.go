package service

import (
	"goApi/internal/models/entity"
	"goApi/internal/repository"
	"goApi/pkg/enum"
	"goApi/pkg/util/helper"
)

//确认回收订单的页面数据
func List(userId, pageInt, limitInt int64) helper.Response {
	var orderM entity.OrderRecycle
	var dataMap = make(map[string]interface{}, 3)
	//
	orderM.RecyclerId = userId

	orderList, err := repository.OrderRecycleRepo.OrderList(orderM, pageInt, limitInt)
	if err != nil {
		resp := helper.RespError(helper.GetUsrAErrMsg(enum.ProcessRepositoryMsg, enum.BusinessOrderMsg, enum.SpecificErrorFindMsg),
			helper.GetUsrAErrCode(enum.ProcessRepositoryCode, enum.BusinessOrderCode, enum.SpecificErrorFindCode), dataMap)
		return resp
	}
	dataMap["OrderList"] = orderList
	resp := helper.RespSuccess("获取订单成功", dataMap)
	return resp
}

//确认回收订单的页面数据
func Detail(orderId, userId int64) helper.Response {

	var orderRepo repository.OrderRepo
	var dataMap = make(map[string]interface{}, 3)

	orderOne, err := orderRepo.FindByOrderId(orderId, userId)
	if err != nil {
		resp := helper.RespError(helper.GetUsrAErrMsg(enum.ProcessRepositoryMsg, enum.BusinessOrderMsg, enum.SpecificErrorFindMsg),
			helper.GetUsrAErrCode(enum.ProcessRepositoryCode, enum.BusinessOrderCode, enum.SpecificErrorFindCode), dataMap)
		return resp
	}

	orderExtInfoList, _ := orderRepo.FindOrderExtInfo(orderOne.Unique)

	dataMap["orderDetail"] = orderOne
	dataMap["orderUserPreCommitInfo"] = orderExtInfoList

	resp := helper.RespSuccess("获取订单成功", dataMap)
	return resp
}
