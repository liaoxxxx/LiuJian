package models

import (
	"fmt"
	orm "goApi/app/models/database"
	"gorm.io/gorm"
)

type User struct {
	ID       int64  `gorm:"primaryKey;autoIncrement:true"`
	Username string `json:"username"` // 列名为 `username`
	Password string `json:"password"` // 列名为 `password`
	Phone    string `json:"phone"`
	Salt     string `json:"salt"`
	gorm.Model
}

func (User) TableName() string {
	return "eb_user"
}

var user []User

//添加
func (uesr User) Insert() (id int64, err error) {

	//添加数据
	result := orm.Eloquent.Create(&uesr)
	id = uesr.ID
	if result.Error != nil {
		err = result.Error
		return
	}
	return
}

//列表
func (uesr *User) Users() (users []User, err error) {
	if err = orm.Eloquent.Find(&uesr).Error; err != nil {
		return
	}
	return
}

//修改
func (uesr *User) Update(id int64) (updateUser User, err error) {

	if err = orm.Eloquent.Select([]string{"id", "username"}).First(&updateUser, id).Error; err != nil {
		return
	}

	//参数1:是要修改的数据
	//参数2:是修改的数据
	if err = orm.Eloquent.Model(&updateUser).Updates(&uesr).Error; err != nil {
		return
	}
	return
}

//单条数据
func (uesr *User) Find(id int64) (user User, res *gorm.DB) {

	var users User
	err := orm.Eloquent.Where(&User{ID: id}).Select("*").Unscoped().Find(&users)
	if err != nil {
		fmt.Println(err.Error)
	}
	return users, err
}

//单条数据
func (uesr *User) FindByPhone(phone string) (user User, res *gorm.DB) {

	var users User
	err := orm.Eloquent.Where(&User{Phone: phone}).Select("*").Unscoped().Find(&users)
	if err != nil {
		fmt.Println(err.Error)
	}
	return users, err
}

//删除数据
func (uesr *User) Destroy(id int64) (Result User, err error) {

	if err = orm.Eloquent.Select([]string{"id"}).First(&uesr, id).Error; err != nil {
		return
	}

	if err = orm.Eloquent.Delete(&uesr).Error; err != nil {
		return
	}
	Result = *uesr
	return
}
