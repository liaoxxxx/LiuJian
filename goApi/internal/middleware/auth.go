package middleware

import (
	"fmt"
	"github.com/gin-gonic/gin"
	"goApi/pkg/util/helper"
	"net/http"
)

func UserAuth() gin.HandlerFunc {
	return func(c *gin.Context) {
		token := c.GetHeader("token")
		//url:=c.Request.URL
		fmt.Println("--------------  middleware  before --------------")
		fmt.Println(token)
		fmt.Println("--------------  middleware  token ^ --------------")
		if token != "" {
			jwtTool := helper.NewJWT()
			userClaims, err := jwtTool.ParseToken(token)

			if err != nil {
				c.JSON(http.StatusUnauthorized, gin.H{"message": "token错误，原因：" + err.Error()})
			} else {
				c.Set("uid", userClaims.ID)
				// 验证通过，会继续访问下一个中间件
				c.Next()
			}

		} else {
			// 验证不通过，不再调用后续的函数处理
			c.Abort()
			c.JSON(http.StatusUnauthorized, gin.H{"message": "token不存在,访问未授权"})
			// return可省略, 只要前面执行Abort()就可以让后面的handler函数不再执行
			return
		}
	}
}
