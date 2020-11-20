package midleware

import (
	"fmt"
	"github.com/gin-gonic/gin"
	"net/http"
)

func UserAuth() gin.HandlerFunc {
	return func(c *gin.Context) {
		token := c.GetHeader("token")
		//url:=c.Request.URL

		fmt.Println("--------------  midleware  before --------------")
		fmt.Println(token)
		fmt.Println("--------------  midleware  token ^ --------------")
		if token != "" {
			// 验证通过，会继续访问下一个中间件
			c.Next()
		} else {
			// 验证不通过，不再调用后续的函数处理
			c.Abort()
			c.JSON(http.StatusUnauthorized, gin.H{"message": "token不存在,访问未授权"})
			// return可省略, 只要前面执行Abort()就可以让后面的handler函数不再执行
			return
		}
	}
}
