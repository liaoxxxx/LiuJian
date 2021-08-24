package mongodb

import "time"

type Auth struct {
	Unique           string   `json:"unique" ;bson:"name"`
	Uid              int64    `json:"uid"  ;bson:"uid"`
	UserUploadPhotos []string `json:"user_upload_photos" ;bson:"user_upload_photos"`
	WeightCateId     int64    `json:"user_weight_cate_id"  ;bson:"weight_cate_id"`
	WeightCateStr    string   `json:"user_weight_cate_str"  ;bson:"user_weight_cate_str"`
	RecCateStr       string   `json:"user_rec_cate_str"  ;bson:"user_rec_cate_str"`
	RecCateId        int64    `json:"user_rec_cate_id"  ;bson:"user_rec_cate_id"`
	CreateTime       time.Time
}

func (Auth *Auth) CollectionName() string {
	return "rec_order_info_ext"
}
