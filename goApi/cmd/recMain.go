package main

import (
	"goApi/router"
)

func main() {

	//回收员api
	recRouter := router.InitRecRouter()
	_ = recRouter.Run(":8009")

}
