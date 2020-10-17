package main

import (
	orm "goApi/database"
	"goApi/router"
)

func main() {
	defer orm.Eloquent.Close()
	initRouter := router.InitRouter()
	_ = initRouter.Run(":8001")

}
