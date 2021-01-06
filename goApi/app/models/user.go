package models

import (
	"fmt"
	orm "goApi/app/models/database"
	"gorm.io/gorm"
)

type User struct {
	ID              int64   `gorm:"primaryKey;autoIncrement:true"`
	Username        string  `json:"username"` // 列名为 `username`
	Password        string  `json:"password"` // 列名为 `password`
	Phone           string  `json:"phone"`
	Salt            string  `json:"salt"`
	RecycleIncome   float64 `json:"recycle_income"`
	RecycleIntegral int64   `json:"recycle_integral"`
	RecycleWeight   float64 `json:"recycle_weight"`
	gorm.Model
}

type UserStatInfo struct {
	Uid             int64
	RecycleIncome   float64
	RecycleIntegral int64
	RecycleWeight   float64
}

func (User) TableName() string {
	return "eb_user"
}

//添加
func (user User) Insert() (id int64, err error) {

	//添加数据
	result := orm.Eloquent.Create(&user)
	id = user.ID
	if result.Error != nil {
		err = result.Error
		return
	}
	return
}

//列表
func (user *User) Users() (users []User, err error) {
	if err = orm.Eloquent.Find(&user).Error; err != nil {
		return
	}
	return
}

//修改
func (user *User) Update(id int64) (updateUser User, err error) {

	if err = orm.Eloquent.Select([]string{"id", "username"}).First(&updateUser, id).Error; err != nil {
		return
	}

	//参数1:是要修改的数据
	//参数2:是修改的数据
	if err = orm.Eloquent.Model(&updateUser).Updates(&user).Error; err != nil {
		return
	}
	return
}

//单条数据
func (user *User) Find(id int64) (userOne User, err error) {

	err = orm.Eloquent.Where(&User{ID: id}).Select("*").Unscoped().Find(&userOne).Error
	if err != nil {
		fmt.Println(err.Error())
	}
	return
}


//删除数据
func (user *User) Destroy(id int64) (Result User, err error) {

	if err = orm.Eloquent.Select([]string{"id"}).First(&user, id).Error; err != nil {
		return
	}

	if err = orm.Eloquent.Delete(&user).Error; err != nil {
		return
	}
	Result = *user
	return
}

//删除数据
func (user *User) GetStateInfo(uid int64) (UserStatInfo UserStatInfo, err error) {
	userOne, _ := user.Find(uid)
	UserStatInfo.RecycleIncome = userOne.RecycleIncome
	UserStatInfo.RecycleIntegral = userOne.RecycleIntegral
	UserStatInfo.RecycleWeight = userOne.RecycleWeight
	return
}
