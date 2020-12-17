package models

import (
	"fmt"
	orm "goApi/app/models/database"
	"goApi/util/helper"
	"gorm.io/gorm"
)

type SystemGroup struct {
	ID         int64  `gorm:"primaryKey;autoIncrement:true"`
	Name       string `json:"name"` // 列名为 `username`
	Info       string `json:"info"` // 列名为 `password`
	ConfigName string `json:"config_name"`
	Fields     string `json:"fields"`
	//SystemGroupDataList []SystemGroupData `gorm:"ForeignKey:ID;AssociationForeignKey:Gid"`
	gorm.Model
}

func (SystemGroup) TableName() string {
	return "eb_system_group"
}

type SystemGroupData4Value struct {
	ID         int64
	Name       string
	Info       string
	ConfigName string
	Fields     string
	Value      string
}

func (systemGroup *SystemGroup) GetField(gid int64) (sysGroup SystemGroup, err error) {
	err = orm.Eloquent.Where(&SystemGroup{ID: gid}).Find(&sysGroup).Error
	return
}

func (systemGroup *SystemGroup) GetDataByConfigName(configName string) (sysGroup []SystemGroupData4Value, err error) {

	//var sysGroupDataList []SystemGroupData
	//err = orm.Eloquent.Model(&sysGroup).Where(&SystemGroup{ConfigName: configName}).Find(&sysGroup).Error
	//errStr:= orm.Eloquent.Model(&sysGroup).Association("SystemGroupDataList").Error
	//sysGroupDataErr:=orm.Eloquent.Model(&sysGroup).Where(&sysGroup).Association("SystemGroupDataList").Find(&sysGroupDataList)
	//fmt.Println("==============================")
	//fmt.Println(sysGroupDataList)
	//fmt.Println("========= err   =====================")
	////fmt.Println(err)
	//fmt.Println(sysGroupDataErr)
	//fmt.Println("========= error   =====================")
	joinStr := helper.JoinTable(systemGroup.TableName(), SystemGroupData{}.TableName(), "id", "gid", helper.InnerJoin)

	err = orm.Eloquent.Model(&SystemGroup{}).Select("*").Joins(joinStr).Where(&SystemGroup{ConfigName: configName}).Scan(&sysGroup).Error
	fmt.Println("-------------------------")
	fmt.Println(joinStr)
	return
}
