package models

import (
	"fmt"
	orm "goApi/app/models/database"
	"gorm.io/gorm"
)

type SystemGroup struct {
	ID                  int64             `gorm:"primaryKey;autoIncrement:true"`
	Name                string            `json:"name"` // 列名为 `username`
	Info                string            `json:"info"` // 列名为 `password`
	ConfigName          string            `json:"config_name"`
	Fields              string            `json:"fields"`
	SystemGroupDataList []SystemGroupData `gorm:"foreignKey:ID;references:Gid"`
	gorm.Model
}

func (SystemGroup) TableName() string {
	return "eb_system_group"
}

func (systemGroup *SystemGroup) GetField(gid int64) (sysGroup SystemGroup, err error) {
	err = orm.Eloquent.Where(&SystemGroup{ID: gid}).Find(&sysGroup).Error
	return
}

func (systemGroup *SystemGroup) GetDataByConfigName(configName string) (sysGroup SystemGroup, err error) {
	sysGroupTmp := SystemGroup{ConfigName: configName}
	var SystemGroupDataList []SystemGroupData
	errstr := orm.Eloquent.Model(&sysGroupTmp).Association("SystemGroupDataList").Find(&sysGroup).Error()
	fmt.Println("==============================")
	fmt.Println(SystemGroupDataList)
	fmt.Println("========= err   =====================")
	fmt.Println(errstr)
	fmt.Println(err)
	fmt.Println("========= error   =====================")
	return
}
