package controller

import (
	"fmt"
	"github.com/gin-gonic/gin"
	userPld "goApi/app/payload/user"
	"goApi/app/repository"
	userService "goApi/app/service/user"
	"goApi/util/helper"
	"net/http"
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

}

func AddrSave(ctx *gin.Context) {
	var userAddrAddPld userPld.UAddressAdd
	helper.BindQuery(ctx, &userAddrAddPld)
	var usrAddrRepo repository.UserAddressRepo
	usrAddrRepo.Save()

}

func AddrDel(ctx *gin.Context) {

}
