package repository

import (
	"context"
	"go.mongodb.org/mongo-driver/bson"
	orm "goApi/app/models/database"
	"goApi/app/models/mongodb"
)

type OrderRepo struct {
}

func (orderRepo OrderRepo) FindOrderByUnique(Unique string) (order orm.Order, err error) {
	err = orm.Eloquent.Model(orm.Order{}).Where("unique", Unique).Find(&order).Error
	if err == nil {
		return order, err
	}
	return orm.Order{}, err
}

func (orderRepo OrderRepo) FindByOrderId(OrderId, userId int64) (order orm.Order, err error) {
	err = orm.Eloquent.Model(orm.Order{}).Where(orm.Order{ID: OrderId, Uid: userId}).Find(&order).Error
	if err == nil {
		return order, err
	}
	return orm.Order{}, err
}

//添加
func (orderRepo OrderRepo) Create(order orm.Order, orderPreCommitList []mongodb.OrderInfoExt) (id int64, err error) {

	//添加数据 到mysql
	result := orm.Eloquent.Create(&order)
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
func (orderRepo OrderRepo) OrderList(order orm.Order, pageInt, limitInt int64) (orderList []orm.Order, err error) {
	err = orm.Eloquent.Where(&order).Limit(int(limitInt)).Offset(int((pageInt - 1) * limitInt)).Find(&orderList).Error
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
