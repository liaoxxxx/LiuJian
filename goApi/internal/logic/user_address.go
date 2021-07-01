package logic

import (
	"goApi/internal/app/user_module/payload/user"
	"goApi/internal/models/entity"
	"goApi/pkg/util/amap"
	"strconv"
	"time"
)

type userAddressLogic struct {
}

var UserAddressLogic userAddressLogic

func (*userAddressLogic) LocationEmptyHandle(address *entity.UserAddress) error {
	geoCode, err := amap.AmapTool.GeoCode(address.Detail, address.City)
	if err != nil {
		return err
	}
	location, err := amap.AmapTool.GetLocation(geoCode)
	if err != nil {
		return err
	}
	address.Longitude = strconv.FormatFloat(location.Lng, 'e', 8, 64)
	address.Latitude = strconv.FormatFloat(location.Lat,
		'e', 8, 64)
	return nil
}

func (*userAddressLogic) BuildByPayload(uAddrPld user.UAddressAdd, userId int64) (uAddress entity.UserAddress) {
	if uAddrPld.Id > 0 {
		uAddress.ID = uAddrPld.Id
	}
	uAddress.AddTime = time.Now().Unix()
	uAddress.City = uAddrPld.City
	uAddress.IsDefault = int8(uAddrPld.IsDefault)
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
