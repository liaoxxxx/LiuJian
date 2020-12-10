package models

import (
	"encoding/json"
	"fmt"
	orm "goApi/app/models/database"
	"gorm.io/gorm"
)

type SystemGroupData struct {
	ID     int64  `gorm:"primaryKey;autoIncrement:true"`
	Gid    int64  `json:"gid"`
	Value  string `json:"value"`
	Sort   int32  `json:"sort"`
	Status int8   `json:"status"`
	gorm.Model
}

type SystemGroupDataValue struct {
	ID     int64  `gorm:"primaryKey;autoIncrement:true"`
	Gid    int64  `json:"gid"`
	Value  string `json:"value"`
	Sort   int32  `json:"sort"`
	Status int8   `json:"status"`
}

func (SystemGroupData) TableName() string {
	return "eb_system_group_data"
}

//列表
func (systemGroupData *SystemGroupData) GetList(gid int64) (data map[string]interface{}, err error) {
	sysGroupDataList := make([]SystemGroupData, 3)
	err = orm.Eloquent.Where(&SystemGroupData{Gid: gid}).Find(&sysGroupDataList).Error

	for i := 0; i < len(sysGroupDataList); i++ {
		var byt = []byte(sysGroupDataList[i].Value)
		_ = json.Unmarshal(byt, &data)

		fmt.Println(data)
	}
	return

}
