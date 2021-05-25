package repository

import (
	"goApi/internal/app/recycler_module/export"
	"goApi/internal/models/database"
	"goApi/internal/models/entity"
)

type recyclerRepo struct {
}

var RecyclerRepo = recyclerRepo{}

//phone 单条数据
func (*recyclerRepo) FindByPhone(phone string) (recycler entity.Recycler, err error) {

	err = database.Eloquent.Where("phone", phone).Unscoped().First(&recycler).Error
	return
}

//phone 单条数据
func (*recyclerRepo) FindByUid(UserId int64) (userOne entity.Recycler, err error) {

	err = database.Eloquent.Model(userOne).Where("id", UserId).Select("*").Unscoped().First(&userOne).Error
	return userOne, err
}

//统计数据
func (*recyclerRepo) GetStateInfo(uid int64) (stat export.RecyclerWorkStat, err error) {
	/*userOne, _ := database.Eloquent.Where("id", uid).Select("*").Unscoped().First(&userOne).Error
			UserStatInfo.RecycleIncome = userOne.RecycleIncome
	UserStatInfo.RecycleIntegral = userOne.RecycleIntegral
	UserStatInfo.RecycleWeight = userOne.RecycleWeight*/
	return
}
