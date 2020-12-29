package middleware

import (
	"github.com/gin-gonic/gin"
	"net/http"
)

func Cors() gin.HandlerFunc {
	return func(c *gin.Context) {
		method := c.Request.Method

		c.Header("Access-Control-Allow-Origin", "*")
		c.Header("Access-Control-Allow-Methods", "GET,POST,PATCH,PUT,DELETE,OPTIONS,DELETE")
		c.Header("Access-Control-Allow-Credentials", "true")

		c.Header("Access-Control-Allow-Headers", "Authori-zation,Authorization, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-Requested-With, AccessToken,X-CSRF-Token, Authorization, Token, token")

		c.Header("Access-Control-Max-Age", "1728000")
		c.Header("Content-Type", "application/json; charset=utf-8")
		c.Header("Access-Control-Expose-Headers", "Content-Length, Access-Control-Allow-Origin, Access-Control-Allow-Headers, Content-Type")







		//放行所有OPTIONS方法
		if method == "OPTIONS" {
			c.AbortWithStatus(http.StatusNoContent)
		}

		//处理请求
		c.Next()

	}
}