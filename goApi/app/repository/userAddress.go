package repository

import (
	"goApi/app/models"
	orm "goApi/app/models/database"
)

type UserAddress struct {
}

//所有地址
func (userAddrRepo UserAddress) AddressList(userId int64) (userAddressList []models.UserAddress, err error) {
	err = orm.Eloquent.Model(models.UserAddress{}).Where("uid", userId).Find(&userAddressList).Error
	if err == nil {
		return userAddressList, err
	}
	return []models.UserAddress{}, err
}
