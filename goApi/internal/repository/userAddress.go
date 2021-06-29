package repository

import (
	"fmt"
	"goApi/internal/app/user_module/payload/user"
	"goApi/internal/models/database"
	"goApi/internal/models/entity"
	"time"
)

type userAddressRepo struct {
}

var UserAddressRepo userAddressRepo

//所有地址
func (userAddrRepo userAddressRepo) AddressList(userId int64) (userAddressList []entity.UserAddress, err error) {
	err = database.Eloquent.Model(entity.UserAddress{}).Where("uid", userId).Find(&userAddressList).Error
	if err == nil {
		return userAddressList, err
	}
	return []entity.UserAddress{}, err
}

func (userAddrRepo userAddressRepo) Save(userAddress entity.UserAddress) (entity.UserAddress, int64) {
	db := database.Eloquent.Save(&userAddress)
	return userAddress, db.RowsAffected
}

func (userAddrRepo userAddressRepo) Find(addressId, userId int64) (uAddress entity.UserAddress, err error) {
	uAddress.Uid = userId
	uAddress.ID = addressId
	err = database.Eloquent.Where(&uAddress).Find(&uAddress).Error
	return uAddress, err
}

func (userAddrRepo userAddressRepo) Del(addressId, userId int64) (int64, error) {
	var address = entity.UserAddress{Uid: userId, ID: addressId}
	fmt.Println(address.ID)
	fmt.Println(address.Uid)
	db := database.Eloquent.Where(&address).Delete(&address)
	return db.RowsAffected, db.Error
}

func (userAddrRepo userAddressRepo) BuildByPayload(uAddrPld user.UAddressAdd, userId int64) (uAddress entity.UserAddress) {

	uAddress.AddTime = time.Now().Unix()
	uAddress.City = uAddrPld.City
	uAddress.Phone = uAddrPld.Phone
	uAddress.PostCode = uAddrPld.PostCode
	uAddress.Province = uAddrPld.Province
	uAddress.City = uAddrPld.City
	uAddress.District = uAddrPld.District
	uAddress.Detail = uAddrPld.Detail
	uAddress.Uid = userId
	uAddress.RealName = uAddrPld.RealName
	return uAddress
}
