package order

import (
	"fmt"
	"github.com/gin-gonic/gin"
	"goApi/app/enum"
	"goApi/app/models"
	"goApi/app/models/mongodb"
	orderPld "goApi/app/payload/order"
	"goApi/app/repository"
	"goApi/util/helper"
	"math/rand"
	"strconv"
	"time"
)

//获取移动端 首页数据
func Create(c *gin.Context, userId int64) helper.Response {
	var orderPld orderPld.Creator
	var err error
	if err := helper.BindQuery(c, &orderPld); err != nil {
		resp := helper.RespError(helper.GetErrMsg(enum.AppRecycleManMsg, enum.ProcessServiceMsg, enum.BusinessOrderMsg, enum.SpecificErrorParamUndefinedMsg),
			helper.GetErrCode(enum.AppUserCode, enum.ProcessServiceCode, enum.BusinessOrderCode, enum.SpecificErrorParamUndefinedCode), orderPld)
		return resp
	}
	var orderModel models.Order
	var orderRepo repository.OrderRepo
	orderInfoExtList := make([]mongodb.OrderInfoExt, 3)
	orderModel, err = orderRepo.FindOrderByUnique(orderPld.Unique)
	if err != nil || orderModel.ID > 0 { // 查询错误 || 订单已存在
		fmt.Println(err.Error())
		resp := helper.RespError(helper.GetErrMsg(enum.AppRecycleManMsg, enum.ProcessServiceMsg, enum.BusinessOrderMsg, enum.SpecificErrorFindMsg),
			helper.GetErrCode(enum.AppUserCode, enum.ProcessServiceCode, enum.BusinessOrderCode, enum.SpecificErrorFindCode), orderModel)
		return resp
	}
	buildByOrderCreatePld(&orderModel, orderPld, userId)
	orderInfoExtList = buildOrderInfoExt(orderPld, userId)
	id, err := orderRepo.Create(orderModel, orderInfoExtList)
	if err != nil || id < 0 {
		resp := helper.RespError(helper.GetErrMsg(enum.AppRecycleManMsg, enum.ProcessServiceMsg, enum.BusinessOrderMsg, enum.SpecificErrorInsertMsg),
			helper.GetErrCode(enum.AppUserCode, enum.ProcessServiceCode, enum.BusinessOrderCode, enum.SpecificErrorInsertCode), orderModel)
		return resp
	} else {
		resp := helper.RespSuccess("新增订单成功", orderModel)
		return resp
	}

}

//确认回收订单的页面数据
func AddSkeleton(userId int64) helper.Response {
	var sysGroup models.SystemGroup
	var userAddrRepo repository.UserAddressRepo
	var dataMap = make(map[string]interface{}, 3)
	///
	//回收种类
	recycleCate, _ := sysGroup.GetDataByConfigName("user_client_home_recycle_category")
	dataMap["RecycleCate"] = recycleCate
	//订单生成前的标记
	uniqueId := genUniqueId(userId)
	dataMap["Unique"] = uniqueId
	//
	addressList, _ := userAddrRepo.AddressList(userId)
	dataMap["AddressList"] = addressList
	resp := helper.RespSuccess("", dataMap)
	return resp
}

//确认回收订单的页面数据
func List(userId, pageInt, limitInt int64) helper.Response {
	var orderM models.Order
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
	dataMap["OrderList"] = orderList
	resp := helper.RespSuccess("获取订单成功", dataMap)
	return resp
}

//确认回收订单的页面数据
func Detail(orderId, userId int64) helper.Response {
	var orderM models.Order
	var orderRepo repository.OrderRepo
	var dataMap = make(map[string]interface{}, 3)
	//
	orderM.Uid = userId

	orderList, err := orderRepo.FindByOrderId(orderId, userId)
	if err != nil {
		resp := helper.RespError(helper.GetUsrAErrMsg(enum.ProcessRepositoryMsg, enum.BusinessOrderMsg, enum.SpecificErrorFindMsg),
			helper.GetUsrAErrCode(enum.ProcessRepositoryCode, enum.BusinessOrderCode, enum.SpecificErrorFindCode), dataMap)
		return resp
	}
	dataMap["orderDetail"] = orderList
	resp := helper.RespSuccess("获取订单成功", dataMap)
	return resp
}

/**
 * @Description:
 * @param orderModel
 * @param orderPld
 */
func buildByOrderCreatePld(orderModel *models.Order, orderPld orderPld.Creator, userId int64) {
	orderModel.OrderId = GenOrderId(enum.OrderTypeRecycleShort)
	orderModel.Mark = orderPld.Mark
	orderModel.UserAddressId = orderPld.AddressId
	orderModel.IsPreengage = orderPld.IsPreengage

	timeTmp, _ := time.Parse("2006-01-02 15:04：05", orderPld.PreengageTime)

	orderModel.PreengageTime = timeTmp.Unix()
	orderModel.UserPhone = orderPld.Phone
	orderModel.RealName = orderPld.RealName
	orderModel.Unique = orderPld.Unique
	orderModel.Uid = userId
}

/**
 * @Description:
 * @param orderModel
 * @param orderPld
 */
func buildOrderInfoExt(orderPld orderPld.Creator, userId int64) (orderInfoExtList []mongodb.OrderInfoExt) {
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
