package repository

import (
	orm "goApi/app/models/database"
)

type UserRepo struct {
}

//phone 单条数据
func (userRepo UserRepo) FindByPhone(phone string) (userOne orm.User, err error) {

	err = orm.Eloquent.Model(userOne).Where("phone", phone).Select("*").Unscoped().First(&userOne).Error
	return
}

//phone 单条数据
func (userRepo UserRepo) FindByUid(UserId string) (userOne orm.User, err error) {

	err = orm.Eloquent.Model(userOne).Where("id", UserId).Select("*").Unscoped().First(&userOne).Error
	return
}
