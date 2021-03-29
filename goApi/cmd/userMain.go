package main

import (
	"goApi/router"
)

func main() {

	//客户api
	initRouter := router.InitUserRouter()
	_ = initRouter.Run(":8008")

}
