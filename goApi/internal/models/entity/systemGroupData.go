package entity

import (
	"encoding/json"
	orm "goApi/internal/models/database"
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
func (systemGroupData *SystemGroupData) GetValueList(gid int64) (sysGroupDataValueList []map[string]interface{}, err error) {
	sysGroupDataList := make([]SystemGroupData, 10)
	err = orm.Eloquent.Where(&SystemGroupData{Gid: gid}).Find(&sysGroupDataList).Error
	dataValue := make(map[string]interface{})
	for i := 0; i <= len(sysGroupDataList)-1; i++ {
		_ = json.Unmarshal([]byte(sysGroupDataList[i].Value), &dataValue)
		sysGroupDataValueList = append(sysGroupDataValueList, dataValue)
	}
	return

}
