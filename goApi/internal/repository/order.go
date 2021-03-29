package repository

import (
	"context"
	"go.mongodb.org/mongo-driver/bson"
	"goApi/internal/models/database"
	"goApi/internal/models/entity"
	"goApi/internal/models/mongodb"
)

type OrderRepo struct {
}

func (orderRepo OrderRepo) FindOrderByUnique(Unique string) (order entity.Order, err error) {
	err = database.Eloquent.Model(entity.Order{}).Where("unique", Unique).Find(&order).Error
	if err == nil {
		return order, err
	}
	return entity.Order{}, err
}

func (orderRepo OrderRepo) FindByOrderId(OrderId, userId int64) (order entity.Order, err error) {
	err = database.Eloquent.Model(entity.Order{}).Where(entity.Order{ID: OrderId, Uid: userId}).Find(&order).Error
	if err == nil {
		return order, err
	}
	return entity.Order{}, err
}

//添加
func (orderRepo OrderRepo) Create(order entity.Order, orderPreCommitList []mongodb.OrderInfoExt) (id int64, err error) {

	//添加数据 到mysql
	result := database.Eloquent.Create(&order)
	//添加回收的旧物数据到mongodb
	for _, preCommit := range orderPreCommitList {
		_, _ = mongodb.MongoClient.Collection(preCommit.CollectionName()).InsertOne(context.Background(), preCommit)
	}

	id = order.ID
	if result.Error != nil {
		err = result.Error
		return
	}
	return
}

//list
func (orderRepo OrderRepo) OrderList(order entity.Order, pageInt, limitInt int64) (orderList []entity.Order, err error) {
	err = database.Eloquent.Where(&order).Limit(int(limitInt)).Offset(int((pageInt - 1) * limitInt)).Find(&orderList).Error
	return
}

func (orderRepo OrderRepo) FindOrderExtInfo(orderUniqueId string) (orderExtInfoList []mongodb.OrderInfoExt, err error) {
	var orderExtModel mongodb.OrderInfoExt
	var filter = bson.M{
		"unique": orderUniqueId,
	}
	err = mongodb.MongoClient.Collection(orderExtModel.CollectionName()).Find(context.Background(), filter).Limit(100).All(&orderExtInfoList)
	return orderExtInfoList, err
}
