package main

import (
	"fmt"
	"goApi/router"

	//"github.com/gin-gonic/gin"
	//"goApi/router"
	"gorm.io/driver/mysql"
	"gorm.io/gorm"
)

func main() {

	dsn := "lj_recycling:fMNss3antjMTFWFD@tcp(111.229.128.239:3306)/lj_recycling?charset=utf8mb4&parseTime=True&loc=Local"
	db, connErr := gorm.Open(mysql.Open(dsn), &gorm.Config{})
	user := User{Username: "Jinzhu", Password: "1234655", Phone: "18676684597"}
	if connErr != nil {
		fmt.Println("-------------------------------")
		fmt.Println(connErr.Error())
	}
	inst := db.Create(&user) // 通过数据的指针来创建
	fmt.Println(inst.Error)

	//defer orm.Eloquent.Close()
	initRouter := router.InitRouter()
	_ = initRouter.Run(":8001")

}
