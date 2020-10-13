package main

import (
	_ "goApi/database"
	orm "goApi/database"
	"goApi/router"
)

func main() {
	defer orm.Eloquent.Close()
	initRouter := router.InitRouter()
	_ = initRouter.Run(":8001")
}
