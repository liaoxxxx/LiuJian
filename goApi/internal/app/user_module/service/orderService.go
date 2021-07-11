package service

import (
	"encoding/json"
	"goApi/configs"
	orderPld "goApi/internal/app/user_module/payload/order"
	"goApi/internal/logic"
	"goApi/internal/models/entity"
	"goApi/internal/models/mongodb"
	"goApi/internal/repository"
	"goApi/pkg/enum"
	enum2 "goApi/pkg/enum"
	"goApi/pkg/logger"
	"goApi/pkg/util"
	"goApi/pkg/util/helper"
	"math/rand"
	"strconv"
	"time"
)

//获取移动端 首页数据
func Create(orderPld orderPld.Creator, userId int64) helper.ServiceResp {
	var svcResp helper.ServiceResp
	var orderModel entity.Order
	var orderRepo repository.OrderRepo
	var err error
	var orderPreCommitList = make([]mongodb.OrderInfoExt, 0)
	usrAddr, err := repository.UserAddressRepo.Find(orderPld.AddressId, userId)
	if err != nil {
		svcResp.Message = enum.UserAddressNotFoundMsg
		svcResp.Code = enum.UserAddressNotFoundCode
		return svcResp
	}
	if usrAddr.Longitude == "" || usrAddr.Latitude == "" {
		err = logic.UserAddressLogic.LocationEmptyHandle(&usrAddr)
		if err != nil {
			logger.Logger.Info(err.Error())
			svcResp.Code = enum.DefaultRequestErrCode
			svcResp.Message = enum.DefaultRequestErrMsg
			return svcResp
		}
	}
	orderModel, err = orderRepo.FindOrderByUnique(orderPld.Unique)
	if err != nil || orderModel.ID > 0 { // 查询错误 || 订单已存在
		svcResp.Message = enum.OrderMainExistMsg
		svcResp.Code = enum.OrderMainExistCode
		return svcResp
	}
	//用户预提交的订单信息
	buildByOrderCreatePld(&orderModel, orderPld, userId, usrAddr)
	orderRec := buildOrderRecycleInfo(orderPld, userId, usrAddr)
	orderRecJson, _ := json.Marshal(orderRec)
	err = util.RabbitMQClient.Publish(configs.TOPICS_ORDER_USER_ISSUE, orderRecJson)
	if err != nil {
		logger.Logger.Info("RabbitMQConnect.Publish(orderRecJson )  err: " + err.Error())
	} else {
		err = repository.DebugLog.InsertLog("RabbitMQConnect.Publish to TOPICS_ORDER_USER_ISSUE success")
	}
	if err != nil {
		return helper.ServiceResp{}
	}
	orderPreCommitList = buildOrderPreCommitInfo(orderPld, userId)
	id, err := orderRepo.Create(orderModel, orderPreCommitList)
	if err != nil || id < 0 {
		svcResp.Message = enum.OrderMainCreateMsg
		svcResp.Code = enum.OrderMainCreateCode
		return svcResp
	} else {

		svcResp.Message = "新增订单成功"
		svcResp.Code = enum.DefaultSuccessCode
		svcResp.Data = map[string]interface{}{"order": orderModel}
		return svcResp
	}

}

//确认回收订单的页面数据
func AddSkeleton(userId int64) helper.Response {
	var sysGroup entity.SystemGroup
	var dataMap = make(map[string]interface{}, 3)
	///
	//回收种类
	recycleCate, _ := sysGroup.GetDataByConfigName("user_client_home_recycle_category")
	dataMap["RecycleCate"] = recycleCate
	//订单生成前的标记
	uniqueId := genUniqueId(userId)
	dataMap["Unique"] = uniqueId
	//
	addressList, _ := repository.UserAddressRepo.AddressList(userId)
	dataMap["AddressList"] = addressList
	resp := helper.RespSuccess("", dataMap)
	return resp
}

//确认回收订单的页面数据
func List(userId, pageInt, limitInt int64) helper.Response {
	var orderM entity.Order
	var orderRepo repository.OrderRepo
	var dataMap = make(map[string]interface{}, 3)
	//
	orderM.Uid = userId

	orderList, err := orderRepo.OrderList(orderM, pageInt, limitInt)
	if err != nil {
		resp := helper.RespError(helper.GetUsrAErrMsg(enum.ProcessRepositoryMsg, enum.BusinessOrderMsg, enum.SpecificErrorFindMsg),
			helper.GetUsrAErrCode(enum.ProcessRepositoryCode, enum.BusinessOrderCode, enum.SpecificErrorFindCode), dataMap)
		return resp
	}
	dataMap["orderList"] = orderList
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

/**
 * @Description:
 * @param orderModel
 * @param orderPld
 */
func buildByOrderCreatePld(orderModel *entity.Order, orderPld orderPld.Creator, userId int64, address entity.UserAddress) {
	addrJson, _ := json.Marshal(address)

	orderModel.OrderId = GenOrderId(enum2.OrderTypeRecyclePreShort)
	orderModel.Mark = orderPld.Mark
	orderModel.UserAddressId = orderPld.AddressId
	orderModel.UserAddress = string(addrJson)
	orderModel.IsPreengage = orderPld.IsPreengage

	timeTmp, _ := time.Parse("2006-01-02 15:04：05", orderPld.PreengageTime)

	orderModel.PreengageTime = timeTmp.Unix()
	orderModel.UserPhone = orderPld.Phone
	orderModel.RealName = orderPld.RealName
	orderModel.Unique = orderPld.Unique
	orderModel.Uid = userId
	orderModel.AddTime = time.Now().Unix()
}

/**
 * @Description:
 * @param orderModel
 * @param orderPld
 */
func buildOrderPreCommitInfo(orderPld orderPld.Creator, userId int64) (orderInfoExtList []mongodb.OrderInfoExt) {
	var orderInfoExt mongodb.OrderInfoExt
	orderInfoExt.Unique = orderPld.Unique
	orderInfoExt.Uid = userId
	if len(orderPld.RecycleProductList) > 0 {
		for _, product := range orderPld.RecycleProductList {
			orderInfoExt.UserUploadPhotos = product.Photos
			orderInfoExt.WeightCateId = product.WeightCateId
			orderInfoExt.WeightCateStr = product.WeightCateStr
			orderInfoExt.RecCateStr = product.RecCateStr
			orderInfoExt.RecCateId = product.RecCateId
			orderInfoExt.CreateTime = time.Now()
			orderInfoExtList = append(orderInfoExtList, orderInfoExt)
		}
	}
	return orderInfoExtList
}

/**
 * @Description:
 * @param orderModel
 * @param orderPld
 */
func buildOrderRecycleInfo(orderPld orderPld.Creator, userId int64, address entity.UserAddress) entity.OrderRecycle {
	addrJson, _ := json.Marshal(address)
	startLng, _ := strconv.ParseFloat(address.Longitude, 64)
	startLat, _ := strconv.ParseFloat(address.Latitude, 64)
	var orderRec = entity.OrderRecycle{
		UserId: userId,
		Unique: orderPld.Unique,
		//RecyclerId    int64  `json:"recycler_id"` //回收员
		UserAddressId: address.ID,
		UserAddress:   string(addrJson),
		CityId:        address.CityId,
		StartLat:      startLat, //起点纬度
		StartLng:      startLng, //起点经度
		//EndLat         float64 `json:"end_lat"`         //终点纬度
		//EndLng         float64 `json:"end_lng"`         //终点经度
		//LinearDistance float64 `json:"linear_distance"` //直线距离，千米
		//RouteDistance  float64 `json:"route_distance"`  //路线距离
		//RecycleAmount float64 `json:"recycle_amount"` //回收费用
		//RecycleStatus int8    `json:"recycle_status"` //回收状态码
		//SiteId        int64   `json:"site_id"`        // 站点
		//AgentId       int64   `json:"agent_id"`       //代理
		IsWithdrawFinish: 0,
		IsWithdrawApply:  0,
		IsDelete:         0,
		IsSystemDel:      0,
		SystemConfirm:    0,
		CreateAt:         time.Now(),
		UpdateAt:         time.Now(),
	}

	return orderRec
}

func GenOrderId(orderType string) string {
	timeStr := time.Now().Unix()
	randStr := rand.Int63n(timeStr)
	return orderType + strconv.FormatInt(timeStr, 10) + strconv.FormatInt(randStr, 10)
}

//获取  订单 uniqueId
func genUniqueId(uid int64) string {
	var timeTamp int64 = time.Now().Unix()
	return helper.MD5(strconv.FormatInt(uid, 10) + strconv.FormatInt(timeTamp, 10))
}
