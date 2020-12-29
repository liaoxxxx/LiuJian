package models

import (
	orm "goApi/app/models/database"
)

type User struct {
	ID            int64   `gorm:"primaryKey;autoIncrement:true"`
	OrderId       string  `json:"order_id"` // 列名为 `username`
	Uid           string  `json:"uid"`      // 列名为 `password`
	RealName      string  `json:"real_name"`
	UserPhone     string  `json:"user_phone"`
	UserAddress   float64 `json:"user_address"`
	UserAddressId int64   `json:"user_address_id"`
	TotalNum      int64   `json:"total_num"`
	TotalPrice    float64 `json:"total_price"`
	PayPrice      float64 `json:"pay_price"`
	TakeTime      int64   `json:"take_time"`
	PayTIme       int64   `json:"pay_time"`
	PayType       float64 `json:"pay_type"`
	AddTime       int64   `json:"add_time"`
	Status        float64 `json:"status"`
	GainIntegral  float64 `json:"gain_integral"`
	Mark          float64 `json:"mark"`
	IsDel         float64 `json:"is_del"`
	Unique        float64 `json:"unique"`
	Remark        float64 `json:"remark"`
	MerId         int64   `json:"mer_id"`
	SiteId        int64   `json:"site_id"`
	IsChanel      float64 `json:"is_channel"`
	IsRemind      float64 `json:"is_remind"`
	IsSystemDel   float64 `json:"is_system_del"`
}

func (User) TableName() string {
	return "eb_store_order"
}

//添加
func (user User) Create() (id int64, err error) {

	//添加数据
	result := orm.Eloquent.Create(&user)
	id = user.ID
	if result.Error != nil {
		err = result.Error
		return
	}
	return
}
