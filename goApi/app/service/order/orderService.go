package order

import (
	"fmt"
	"github.com/gin-gonic/gin"
	orderEnum "goApi/app/enum"
	models "goApi/app/models"
	orderPld "goApi/app/payload/order"
	"goApi/app/repository"
	"goApi/util/helper"
	"math/rand"
	"strconv"
	"time"
)

//获取移动端 首页数据
func Create(c *gin.Context) helper.Response {
	var orderPld orderPld.Creator
	var err error
	if err := helper.BindQuery(c, &orderPld); err != nil {
		resp := helper.RespError(orderEnum.ParamUndefinedMsg, orderEnum.ParamUndefinedCode, orderPld)
		return resp
	}
	var orderModel models.Order
	var orderRepo repository.OrderRepo
	orderModel, err = orderRepo.FindOrderByUnique(orderPld.Unique)
	if orderModel.ID > 0 {
		resp := helper.RespError(orderEnum.OrderExistedMsg, orderEnum.OrderExistedCode, orderModel)
		return resp
	}
	if err != nil {
		fmt.Println(err.Error())
		resp := helper.RespError(err.Error(), orderEnum.OrderExistedCode, orderModel)
		return resp
	}
	buildByOrderCreatePld(&orderModel, orderPld)
	_, _ = orderRepo.Create(orderModel)
	///

	return helper.Response{}
}

//确认回收订单的页面数据
func AddSkeleton() helper.Response {

	var sysGroup models.SystemGroup
	var dataMap = make(map[string]interface{}, 3)
	///
	//回收种类
	recycleCate, _ := sysGroup.GetDataByConfigName("user_client_home_recycle_category")
	dataMap["RecycleCate"] = recycleCate

	uniqueId := genUniqueId(2)
	dataMap["Unique"] = uniqueId
	resp := helper.RespSuccess("", dataMap)
	return resp
}

/**
 * @Description:
 * @param orderModel
 * @param orderPld
 */
func buildByOrderCreatePld(orderModel *models.Order, orderPld orderPld.Creator) {
	orderModel.OrderId = GenOrderId(orderEnum.OrderTypeRecycleShort)
	orderModel.Mark = orderPld.Mark
	orderModel.UserAddressId = orderPld.AddressId
	orderModel.IsPreengage = orderPld.IsPreengage

	orderModel.PreengageTime = orderPld.PreengageTime
	orderModel.UserPhone = orderPld.Phone
	orderModel.RealName = orderPld.RealName
	orderModel.Unique = orderPld.Unique
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
