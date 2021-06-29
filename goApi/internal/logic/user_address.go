package logic

import (
	"goApi/internal/models/entity"
	"goApi/pkg/util/amap"
	"strconv"
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
