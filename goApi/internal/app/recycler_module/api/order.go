package api

import (
	"fmt"
	"github.com/gin-gonic/gin"
	"goApi/internal/app/recycler_module/service"
	"goApi/pkg/util/helper"
	"net/http"
	"strconv"
)

type orderSerer struct {
}

var OrderServer = new(orderSerer)

//列表数据
func (*orderSerer) Confirm(c *gin.Context) {
	c.JSON(200, gin.H{
		"html": "<b>Hello, world!</b>",
	})
}

//列表数据
func (server *orderSerer) List(ctx *gin.Context) {
	recId := helper.GetRecIdByCtx(ctx)
	page := helper.GetPage(ctx)
	limit := helper.GetLimit(ctx)
	ctx.JSON(http.StatusOK, service.List(recId, page, limit))
}

//列表数据
func Detail(ctx *gin.Context) {
	uid, err := ctx.Get("uid")
	if err == false {
		fmt.Println(err)
	}
	userId := uid.(int64)
	OrderIdStr := ctx.DefaultQuery("orderId", "0")
	OrderId, _ := strconv.ParseInt(OrderIdStr, 10, 64)

	ctx.JSON(http.StatusOK, service.Detail(OrderId, userId))
}
