package main

import (
	"goApi/router"
)

func main() {

	//util.Init()
	//回收员api
	recRouter := router.InitRecRouter()
	_ = recRouter.Run(":8009")

}
