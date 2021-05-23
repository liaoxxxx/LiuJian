package repository

import (
	"goApi/internal/models/database"
	"goApi/internal/models/entity"
)

type recyclerRepo struct {
}

var RecyclerRepo = recyclerRepo{}

//phone 单条数据
func (*recyclerRepo) FindByPhone(phone string) (recycler entity.Recycler, err error) {

	err = database.Eloquent.Model(recycler).Where("phone", phone).Select("*").Unscoped().First(&recycler).Error
	return
}

//phone 单条数据
func (*recyclerRepo) FindByUid(UserId string) (userOne entity.User, err error) {

	err = database.Eloquent.Model(userOne).Where("id", UserId).Select("*").Unscoped().First(&userOne).Error
	return
}
