package models

import (
	orm "goApi/app/models/database"
	"gorm.io/gorm"
)

type SystemGroup struct {
	ID         int64  `gorm:"primaryKey;autoIncrement:true"`
	Name       string `json:"name"` // 列名为 `username`
	Info       string `json:"info"` // 列名为 `password`
	ConfigName string `json:"config_name"`
	Fields     string `json:"fields"`
	gorm.Model
}

func (SystemGroup) TableName() string {
	return "eb_system_group"
}

var systemGroup []SystemGroup

func (systemGroup *SystemGroup) getField(gig int32) (sysGroup SystemGroup, err error) {
	if err = orm.Eloquent.Find(&systemGroup).Error; err != nil {
		return
	}
	return
}
