package controller

import (
	"fmt"
	"github.com/gin-gonic/gin"
	userService "goApi/app/service/user"
	"net/http"
)

//列表数据
func AddrList(ctx *gin.Context) {
	uid, err := ctx.Get("uid")
	if err == false {
		fmt.Println(err)
	}
	userId := uid.(int64)
	resp:= userService.AddrList(userId)
	ctx.JSON(http.StatusOK, resp)
}



func AddrFind(ctx *gin.Context) {

}

func AddrUpdate(ctx *gin.Context) {

}
func AddrAdd(ctx *gin.Context) {

}
func AddrDel(ctx *gin.Context) {

}

