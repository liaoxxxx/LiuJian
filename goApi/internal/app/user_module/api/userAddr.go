package api

import (
	"fmt"
	"github.com/gin-gonic/gin"
	userPld "goApi/internal/app/user_module/payload/user"
	userService "goApi/internal/app/user_module/service/user"
	"goApi/pkg/enum"
	"goApi/pkg/logger"
	"goApi/pkg/util/helper"
	"net/http"
	"strconv"
)

//列表数据
func AddrList(ctx *gin.Context) {
	uid, err := ctx.Get("uid")
	if err == false {
		fmt.Println(err)
	}
	userId := uid.(int64)
	resp := userService.AddrList(userId)
	helper.RespJson(ctx, resp)
}

func AddrFind(ctx *gin.Context) {

	addrIdTemp := ctx.Query("id")
	addrId, _ := strconv.ParseUint(addrIdTemp, 0, 64)
	uid, err := ctx.Get("uid")
	if err == false {
		fmt.Println(err)
	}
	userId := uid.(int64)
	resp := userService.AddrFind(int64(addrId), userId)
	ctx.JSON(http.StatusOK, resp)
}

func AddrSave(ctx *gin.Context) {
	userId := helper.GetUidByCtx(ctx)
	var userAddrAddPld userPld.UAddressAdd
	bindErr := helper.BindQuery(ctx, &userAddrAddPld)
	if bindErr != nil {
		logger.Logger.Info(fmt.Sprintf("UAddressAdd %v", userAddrAddPld))
		ctx.JSON(http.StatusOK, helper.RespError(enum.RequestParamUnexpectErrCode, fmt.Sprintf("%v", bindErr), nil))
		return
	}
	resp := userService.Save(userId, userAddrAddPld)
	helper.RespJson(ctx, resp)

}

func AddrDel(ctx *gin.Context) {
	addrIdTemp := ctx.Query("id")
	fmt.Println("addrIdTemp----------------------")
	fmt.Println(addrIdTemp)
	addrId, _ := strconv.ParseUint(addrIdTemp, 0, 64)
	uid, err := ctx.Get("uid")
	if err == false {
		fmt.Println(err)
	}
	userId := uid.(int64)
	resp := userService.AddrDel(int64(addrId), userId)
	ctx.JSON(http.StatusOK, resp)
}
