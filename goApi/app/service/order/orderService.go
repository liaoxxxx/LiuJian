package order

import (
	"fmt"
	"github.com/gin-gonic/gin"
	orderEnum "goApi/app/enum"
	models "goApi/app/models"
	orderPld "goApi/app/payload/order"
	"goApi/util/helper"
	"math/rand"
	"net/http"
	"strconv"
	"time"
)

//获取移动端 首页数据
func Create(c *gin.Context) *helper.Response {
	var resp = new(helper.Response)
	var orderPld orderPld.Creator
	if err := helper.BindQuery(c, &orderPld); err != nil {
		return nil
	}
	var orderModel models.Order
	buildByOrderCreatePld(&orderModel, orderPld)
	_, _ = orderModel.Create(orderModel)
	///
	resp.ErrCode = 0
	resp.Msg = "success"
	resp.Code = http.StatusOK
	resp.Status = "ok"
	return resp
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
	dataMap["UniqueId"] = uniqueId
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

	//orderModel.TakeTime = orderPld.PreengageTime
	orderModel.UserPhone = orderPld.Phone
	orderModel.RealName = orderPld.RealName
}

func GenOrderId(orderType string) string {
	timeStr := time.Now().Unix()
	fmt.Println(timeStr)

	randStr := rand.Int63n(timeStr)
	fmt.Println(randStr)
	return orderType + strconv.FormatInt(timeStr, 10) + strconv.FormatInt(randStr, 10)
}

//获取  订单 uniqueId
func genUniqueId(uid int64) string {
	var timeTamp int64 = time.Now().Unix()
	return  helper.MD5( strconv.FormatInt(uid,10) +strconv.FormatInt(timeTamp, 10))
}
