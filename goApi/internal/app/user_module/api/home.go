package api

import (
	"fmt"
	"github.com/gin-gonic/gin"
	homeService "goApi/internal/app/user_module/service"
	"goApi/pkg/util/helper"
	"net/http"
)

type homeServer struct {
}

var HomeServer homeServer

//列表数据
func (homeServer) Index(c *gin.Context) {
	c.JSON(200, gin.H{
		"html": "<b>Hello, world!</b>",
	})
}

// Skeleton  首页的骨架
//  @Description:
//  @receiver homeServer
//  @param c
//
func (homeServer) Skeleton(ctx *gin.Context) {
	uid := helper.GetUidByCtx(ctx)
	fmt.Println(uid)
	resp := homeService.GetHomeMobileData(uid)
	ctx.JSON(http.StatusOK, resp)
}
