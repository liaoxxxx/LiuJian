package mongodb

import (
	"context"
	"fmt"
	"github.com/qiniu/qmgo"
)

var MongoClient *qmgo.Database

const DatabaseName = "rec_order_info_main"

func init() {
	ctx := context.Background()
	client, err := qmgo.NewClient(ctx, &qmgo.Config{Uri: "mongodb://47.115.182.67:27017"})
	if err != nil {
		fmt.Println("warring : 【 MongoDB 】 init error -> " + err.Error())
	}
	if client == nil {
		fmt.Println("warring : 【 MongoDB 】 client init error ")
	}
	MongoClient = client.Database(DatabaseName)

}
