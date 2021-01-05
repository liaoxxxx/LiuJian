package repository

import (
	"goApi/app/models"
	orm "goApi/app/models/database"
)

type OrderRepo struct {
}

func (orderRepo OrderRepo) FindOrderByUnique(Unique string) (order models.Order, err error) {
	err = orm.Eloquent.Model(models.Order{}).Where("unique", Unique).Find(&order).Error
	if err == nil {
		return order, err
	}
	return models.Order{}, err
}

//添加
func (orderRepo OrderRepo) Create(order models.Order) (id int64, err error) {

	//添加数据
	result := orm.Eloquent.Create(&order)
	id = order.ID
	if result.Error != nil {
		err = result.Error
		return
	}
	return
}
