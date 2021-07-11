package repository

import (
	"goApi/internal/models/database"
	"goApi/internal/models/entity"
)

type recyclerWorkingRepo struct {
}

var RecyclerWorkingRepo = recyclerWorkingRepo{}

// FindByPhone phone 单条数据
func (*recyclerWorkingRepo) FindByPhone(phone string) (recycler entity.RecyclerWorking, err error) {

	err = database.Eloquent.Where("phone", phone).Unscoped().First(&recycler).Error
	return
}

// FindByUid uid 单条数据
func (*recyclerWorkingRepo) FindByUid(UserId int64) (userOne entity.RecyclerWorking, err error) {

	err = database.Eloquent.Model(userOne).Where("uid", UserId).Select("*").Unscoped().First(&userOne).Error
	return userOne, err
}
