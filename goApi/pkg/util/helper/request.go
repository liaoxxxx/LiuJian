package helper

import (
	"fmt"
	"github.com/gin-gonic/gin"
)

func BindQuery(ctx *gin.Context, obj interface{}) error {
	if err := ctx.ShouldBindJSON(obj); err != nil {
		fmt.Println("解析请求JSON错误,无效的请求参数")
		fmt.Println(err)
	}
	return nil
}
