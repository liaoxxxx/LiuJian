package mongodb

import (
	"github.com/qiniu/qmgo"
)

type RecOrderInfoExt struct {
	Collection *qmgo.Collection
}

func GetColName() string {
	return "rec_order_info_ext"
}

func (recOrderInfoExt RecOrderInfoExt) init() {
	recOrderInfoExt.Collection = MongoDatabase.Collection(GetColName())
}
