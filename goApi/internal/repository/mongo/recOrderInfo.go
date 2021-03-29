package mongo

import (
	"context"
	"fmt"
	"github.com/qiniu/qmgo"
)

var MongoDatabase *qmgo.Database

const DatabaseName = "lj_recycle_mongo"

func init() {
	ctx := context.Background()
	client, err := qmgo.NewClient(ctx, &qmgo.Config{Uri: "mongodb://localhost:27017"})
	if err != nil {
		fmt.Println("warring : 【 MongoDB 】 init error")
	}
	MongoDatabase = client.Database(DatabaseName)
}
