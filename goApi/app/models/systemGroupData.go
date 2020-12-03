package models

import (
	"fmt"
	orm "goApi/app/models/database"
	"gorm.io/gorm"
)

type SystemGroupData struct {
	ID     int64  `gorm:"primaryKey;autoIncrement:true"`
	Gid    int32  `json:"gid"`   // 列名为 `username`
	Value  string `json:"value"` // 列名为 `password`
	Sort   int32  `json:"sort"`
	Status int8   `json:"status"`
	gorm.Model
}

func (SystemGroupData) TableName() string {
	return "eb_system_group"
}

//列表
func (systemGroupData *SystemGroupData) getList(gid int32) (sysGroupDataList []SystemGroupData, res *gorm.DB) {

	res = orm.Eloquent.Where(&SystemGroupData{Gid: gid}).Select("*").Find(&sysGroupDataList)
	if res != nil {
		fmt.Println(res.Error)
	}
	return
}
