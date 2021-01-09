package models

import (
	orm "goApi/app/models/database"
)

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
	AddTime   string `json:"add_time"`
}

func (UserAddress) TableName() string {
	return "eb_user_address"
}

//添加
func (userAddr UserAddress) Insert() (id int64, err error) {

	//添加数据
	result := orm.Eloquent.Create(&userAddr)
	id = userAddr.ID
	if result.Error != nil {
		err = result.Error
		return
	}
	return
}

//列表
func (userAddr *UserAddress) List(uid int64) (userAddress []UserAddress, err error) {
	err = orm.Eloquent.Model(userAddr).Where(&userAddr).Joins(" ").Scan(&userAddress).Error
	if err != nil {
		return
	}
	return
}
