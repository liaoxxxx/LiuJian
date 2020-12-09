package models

import (
	"fmt"
	orm "goApi/app/models/database"
)

type SystemGroupData struct {
	ID     int64  `gorm:"primaryKey;autoIncrement:true"`
	Gid    int64  `json:"gid"`
	Value  string `json:"value"`
	Sort   int32  `json:"sort"`
	Status int8   `json:"status"`
	//gorm.Model
}

func (SystemGroupData) TableName() string {
	return "eb_system_group_data"
}

//列表
func (systemGroupData *SystemGroupData) GetList(gid int64) (sysGroupDataList []SystemGroupData, err error) {
	err = orm.Eloquent.Where(&SystemGroupData{Gid: gid}).Find(&sysGroupDataList).Error
	fmt.Println(sysGroupDataList)
	return

}
