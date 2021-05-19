package controller

import (
	"fmt"
	"github.com/gin-gonic/gin"
	userPld "goApi/internal/app/user_module/payload/user"
	userService "goApi/internal/app/user_module/service/user"
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
	ctx.JSON(http.StatusOK, resp)
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
	uid, err := ctx.Get("uid")
	if !err {
		fmt.Println("fmt.Println(err)")
		fmt.Println(err)
	}
	userId := uid.(int64)
	var userAddrAddPld userPld.UAddressAdd
	bindErr := helper.BindQuery(ctx, &userAddrAddPld)
	if bindErr != nil {
		fmt.Println("helper.BindQuery(ctx, &userAddrAddPld)")
		fmt.Println(err)
	}
	resp := userService.Save(userId, userAddrAddPld)
	ctx.JSON(http.StatusOK, resp)

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
