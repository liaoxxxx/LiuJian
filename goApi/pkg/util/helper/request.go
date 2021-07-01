package helper

import (
	"fmt"
	"github.com/gin-gonic/gin"
)

func BindQuery(ctx *gin.Context, obj interface{}) error {
	if err := ctx.ShouldBind(obj); err != nil {
		fmt.Println("解析请求payload错误form-data,无效的请求参数")
		if err = ctx.ShouldBindJSON(obj); err != nil {
			fmt.Println("解析请求payload错误(json),无效的请求参数")
			fmt.Println(err.Error())
			return err
		}
	}
	return nil
}

//客户端 post  Content-type: application/json
func BindJson(ctx *gin.Context, obj interface{}) error {
	err := ctx.ShouldBindJSON(obj)
	if err != nil {
		fmt.Println("解析请求payload错误,无效的请求参数")
		fmt.Println(err.Error())
	}
	return err

}

// GetUidByCtx
/**
 * @Description: 通过上下文 获取 绑定的uid
 * @param ctx
 * @param obj
 * @return int64
 */
func GetUidByCtx(ctx *gin.Context) int64 {
	uid, exist := ctx.Get("uid")
	if exist == false {
		//fmt.Println(exist)
		return 0
	}
	return uid.(int64)
}

// GetRecIdByCtx
/**
 * @Description: 通过上下文 获取 绑定的uid
 * @param ctx
 * @param obj
 * @return int64
 */
func GetRecIdByCtx(ctx *gin.Context) int64 {
	uid, exist := ctx.Get("recyclerId")
	if exist == false {
		//fmt.Println(exist)
		return 0
	}
	return uid.(int64)
}

/**
 * @Description: 获取查询的页数
 * @param ctx
 * @return int64
 */
func GetPage(ctx *gin.Context) int64 {
	page := ctx.DefaultQuery("page", "1")
	return parseStr2Int(page)
}

/**
 * @Description:   获取查询的单页查询的条目数量
 * @param ctx
 * @return int64
 */
func GetLimit(ctx *gin.Context) int64 {
	page := ctx.DefaultQuery("limit", "10")
	return parseStr2Int(page)
}
