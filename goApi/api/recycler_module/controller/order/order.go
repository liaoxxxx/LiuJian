package order

import (
	"fmt"
	"github.com/gin-gonic/gin"
	orderService "goApi/api/user_module/service/order"
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
	uid, err := ctx.Get("uid")
	if err == false {
		fmt.Println(err)
	}
	userId := uid.(int64)
	ctx.JSON(http.StatusOK, orderService.Create(ctx, userId))
}

//列表数据
func AddSkeleton(c *gin.Context) {
	uid, err := c.Get("uid")
	if err == false {
		fmt.Println(err)
	}
	userId := uid.(int64)
	c.JSON(http.StatusOK, orderService.AddSkeleton(userId))
}

//列表数据
func List(ctx *gin.Context) {
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
