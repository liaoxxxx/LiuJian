package main

import (
	"fmt"
	"goApi/router"
)

func main() {
	fmt.Println("-------------------------------------------")
	//客户api
	initRouter := router.InitUserRouter()
	_ = initRouter.Run(":8008")

}
