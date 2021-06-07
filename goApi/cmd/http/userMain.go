package main

import (
	"goApi/pkg/util"
	"goApi/router"
)

func main() {
	util.Init()
	//客户api
	initRouter := router.InitUserRouter()
	_ = initRouter.Run(":8008")

}
