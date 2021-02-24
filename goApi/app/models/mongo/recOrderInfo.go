package mongo

import (
	"context"
	"fmt"
	"github.com/qiniu/qmgo"
)

var recOrderInfoMongo *qmgo.Collection

const collectionName = "rec_order_info_main"

func init() {
	ctx := context.Background()
	client, err := qmgo.NewClient(ctx, &qmgo.Config{Uri: "mongodb://localhost:27017"})
	if err != nil {
		fmt.Println("warring : 【 MongoDB 】 init error")
	}
	db := client.Database(collectionName)
	recOrderInfoMongo = db.Collection("user")
}
