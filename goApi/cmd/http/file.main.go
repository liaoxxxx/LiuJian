package main

import "goApi/router"

func main() {

	//文件管理 api
	recRouter := router.InitFileServerRouter()
	_ = recRouter.Run(":8010")
}
