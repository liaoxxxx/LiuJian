package repository

import (
	"goApi/app/models"
	orm "goApi/app/models/database"
	"goApi/app/payload/user"
	"time"
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

func (userAddrRepo UserAddressRepo) Save(userAddress models.UserAddress) (models.UserAddress, int64) {
	db := orm.Eloquent.Save(&userAddress)
	return userAddress, db.RowsAffected
}

func (userAddrRepo UserAddressRepo) Find(addressId, userId int64) (uAddress models.UserAddress, err error) {
	uAddress.Uid = userId
	uAddress.ID = addressId
	err = orm.Eloquent.Where(&uAddress).Find(&uAddress).Error
	return uAddress, err
}

func (userAddrRepo UserAddressRepo) BuildByPayload(uAddrPld user.UAddressAdd, userId int64) (uAddress models.UserAddress) {

	uAddress.AddTime = time.Now().Unix()
	uAddress.City = uAddrPld.City
	uAddress.Phone = uAddrPld.Phone
	uAddress.PostCode = uAddrPld.PostCode
	uAddress.Province = uAddrPld.Province
	uAddress.City = uAddrPld.City
	uAddress.Uid = userId
	return uAddress
}
