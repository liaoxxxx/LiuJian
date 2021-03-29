package repository

import (
	"goApi/internal/models/database"
	"goApi/internal/models/entity"
)

type UserRepo struct {
}

//phone 单条数据
func (userRepo UserRepo) FindByPhone(phone string) (userOne entity.User, err error) {

	err = database.Eloquent.Model(userOne).Where("phone", phone).Select("*").Unscoped().First(&userOne).Error
	return
}

//phone 单条数据
func (userRepo UserRepo) FindByUid(UserId string) (userOne entity.User, err error) {

	err = database.Eloquent.Model(userOne).Where("id", UserId).Select("*").Unscoped().First(&userOne).Error
	return
}
