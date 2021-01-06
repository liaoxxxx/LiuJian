package repository

import (
	orm "goApi/app/models/database"
	"goApi/app/models"
)

type UserRepo struct {
}



//phone 单条数据
func (userRepo UserRepo) FindByPhone(phone string) (userOne models.User, err error) {

	err = orm.Eloquent.Model(userOne).Where("phone",phone).Select("*").Unscoped().First(&userOne).Error
	return
}
