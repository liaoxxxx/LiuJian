package repository

import (
	"goApi/app/models"
	orm "goApi/app/models/database"
)

type UserAddressRepo struct {
}

//所有地址
func (userAddrRepo UserAddressRepo) AddressList(userId int64) (userAddressList []models.UserAddress, err error) {
	err = orm.Eloquent.Model(models.UserAddress{}).Where("uid", userId).Find(&userAddressList).Error
	if err == nil {
		return userAddressList, err
	}
	return []models.UserAddress{}, err
}

func (userAddrRepo UserAddressRepo) save(userAddress models.UserAddress) (models.UserAddress, int64) {
	db := orm.Eloquent.Save(userAddress)
	return userAddress, db.RowsAffected
}
