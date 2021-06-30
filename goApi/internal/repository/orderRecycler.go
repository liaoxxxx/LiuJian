package repository

import (
	"context"
	"go.mongodb.org/mongo-driver/bson"
	"goApi/internal/models/database"
	"goApi/internal/models/entity"
	"goApi/internal/models/mongodb"
)

type orderRecycleRepo struct {
}

var OrderRecycleRepo orderRecycleRepo

func (orderRepo orderRecycleRepo) FindOrderByUnique(Unique string) (order entity.Order, err error) {
	err = database.Eloquent.Model(entity.Order{}).Where("unique", Unique).Find(&order).Error
	if err == nil {
		return order, err
	}
	return entity.Order{}, err
}

func (orderRepo orderRecycleRepo) FindByOrderId(OrderId, userId int64) (order entity.Order, err error) {
	err = database.Eloquent.Model(entity.Order{}).Where(entity.Order{ID: OrderId, Uid: userId}).Find(&order).Error
	if err == nil {
		return order, err
	}
	return entity.Order{}, err
}

//添加
func (orderRepo orderRecycleRepo) Create(order entity.OrderRecycle) (id int64, err error) {

	//添加数据 到mysql
	result := database.Eloquent.Create(&order)

	id = order.ID
	if result.Error != nil {
		err = result.Error
		return
	}
	return
}

//list
func (orderRepo orderRecycleRepo) OrderList(order entity.OrderRecycle, pageInt, limitInt int64) (orderList []entity.OrderRecycle, err error) {
	err = database.Eloquent.Where(&order).Limit(int(limitInt)).Offset(int((pageInt - 1) * limitInt)).Find(&orderList).Error
	return
}

func (orderRepo orderRecycleRepo) FindOrderExtInfo(orderUniqueId string) (orderExtInfoList []mongodb.OrderInfoExt, err error) {
	var orderExtModel mongodb.OrderInfoExt
	var filter = bson.M{
		"unique": orderUniqueId,
	}
	err = mongodb.MongoClient.Collection(orderExtModel.CollectionName()).Find(context.Background(), filter).Limit(100).All(&orderExtInfoList)
	return orderExtInfoList, err
}
