package entity

type UserAddress struct {
	ID        int64  `gorm:"primaryKey;autoIncrement:true" json:"id"`
	Uid       int64  `json:"uid"`       // 列名为 `username`
	RealName  string `json:"real_name"` // 列名为 `password`
	Phone     string `json:"phone"`
	TagId     int64  `json:"tag_id"`
	Province  string `json:"province"`
	City      string `json:"city"`
	CityId    int64  `json:"city_id"`
	District  string `json:"district"`
	Detail    string `json:"detail"`
	PostCode  string `json:"post_code"`
	Longitude string `json:"longitude"`
	Latitude  string `json:"latitude"`
	IsDefault int8   `json:"is_default"`
	IsDel     int8   `json:"is_del"`
	AddTime   int64  `json:"add_time"`
}

func (UserAddress) TableName() string {
	return "eb_user_address"
}
