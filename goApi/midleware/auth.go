package midleware

import (
	"fmt"
	"github.com/gin-gonic/gin"
	"log"
	"time"
)

func UserAuth() gin.HandlerFunc {
	return func(c *gin.Context) {
		t := time.Now()

		//config.UserAuthRouters
		// 设置 example 变量
		//c.Set("example", "12345")
		// 请求前
		c.Next()

		token := c.GetHeader("token")
		fmt.Println("--------------  midleware  before --------------")
		fmt.Println(token)
		fmt.Println(c.Request.URL)
		fmt.Println("--------------  midleware  next  --------------")
		// 请求后
		latency := time.Since(t)
		log.Print(latency)

		// 获取发送的 status
		status := c.Writer.Status()
		log.Println(status)
	}
}
