package entity

import (
	"gorm.io/gorm"
)

type RecyclerWorking struct {
	ID               int64   `gorm:"primaryKey;autoIncrement:true" json:"id"`
	Uid              int64   `json:"uid"`
	Phone            string  `json:"phone"`
	CityId           int64   `json:"city_id"`
	CardId           string  `json:"card_id"`
	SpreadUid        int64   `json:"spread_uid"`
	WorkSiteId       int64   `json:"work_site_id"`
	PartnerId        int64   `json:"partner_id"`
	Province         string  `json:"province"`
	City             string  `json:"city"`
	District         string  `json:"district"`
	PostCode         string  `json:"post_code"`
	Longitude        float64 `json:"longitude"`
	Latitude         float64 `json:"latitude"`
	SignNum          int64   `json:"sign_num"`
	ReceivingCounter int64   `json:"receiving_counter"`
	ReceivingAmount  float64 `json:"receiving_amount"`
	SpreadTime       int64   `json:"spread_time"`
	IsWorking        int8    `json:"is_working"`
	IsRegister       int8    `json:"is_register"`
	IsPromoter       int8    `json:"is_promoter"`
	gorm.Model
}

func (*RecyclerWorking) TableName() string {
	return "eb_recycler_working"
}
