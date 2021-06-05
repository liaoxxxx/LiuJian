package api

import (
	"fmt"
	"github.com/gin-gonic/gin"
	orderService "goApi/internal/app/user_module/service/order"
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
	uid, err := ctx.Get("uid")
	if err == false {
		fmt.Println(err)
	}
	userId := uid.(int64)
	page := ctx.DefaultQuery("page", "0")
	limit := ctx.DefaultQuery("limit", "10")
	pageInt, _ := strconv.ParseInt(page, 10, 64)
	limitInt, _ := strconv.ParseInt(limit, 10, 64)

	ctx.JSON(http.StatusOK, orderService.List(userId, pageInt, limitInt))
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

	ctx.JSON(http.StatusOK, orderService.Detail(OrderId, userId))
}
