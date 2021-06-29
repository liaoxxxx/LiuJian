package api

import (
	"fmt"
	"github.com/gin-gonic/gin"
	orderPld "goApi/internal/app/user_module/payload/order"
	"goApi/internal/app/user_module/service"
	"goApi/pkg/enum"
	"goApi/pkg/util/helper"
	"net/http"
	"strconv"
)

//列表数据
func Confirm(c *gin.Context) {
	c.JSON(200, gin.H{
		"html": "<b>Hello, world!</b>",
	})
}

//列表数据
func Create(ctx *gin.Context) {
	userId := helper.GetUidByCtx(ctx)
	var orderPld orderPld.Creator
	if err := helper.BindQuery(ctx, &orderPld); err != nil {
		ctx.JSON(http.StatusOK, helper.RespError(enum.RequestParamUnexpectErrMsg, enum.RequestParamUnexpectErrCode, nil))
		return
	}
	resp := service.Create(orderPld, userId)

	if resp.Code == enum.DefaultSuccessCode {
		ctx.JSON(http.StatusOK, helper.RespSuccess(resp.Message, resp.Data))
	} else {
		ctx.JSON(http.StatusOK, helper.RespError(resp.Message, resp.Code, resp.Data))
	}

}

// AddSkeleton 列表数据
/**
 * @Description:
 * @param c
 */
func AddSkeleton(c *gin.Context) {
	uid, err := c.Get("uid")
	if err == false {
		fmt.Println(err)
	}
	userId := uid.(int64)
	c.JSON(http.StatusOK, service.AddSkeleton(userId))
}

// List 列表数据
func List(ctx *gin.Context) {
	userId := helper.GetUidByCtx(ctx)
	page := helper.GetPage(ctx)
	limit := helper.GetLimit(ctx)
	ctx.JSON(http.StatusOK, service.List(userId, page, limit))
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
