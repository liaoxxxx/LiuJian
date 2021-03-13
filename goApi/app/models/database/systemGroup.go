package database

import (
	"encoding/json"
	h "goApi/util/helper"
)

type SystemGroup struct {
	ID         int64  `gorm:"primaryKey;autoIncrement:true"`
	Name       string `json:"name"` // 列名为 `username`
	Info       string `json:"info"` // 列名为 `password`
	ConfigName string `json:"config_name"`
	Fields     string `json:"fields"`
	//SystemGroupDataList []SystemGroupData `gorm:"ForeignKey:ID;AssociationForeignKey:Gid"`
	//gorm.Model
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
	DataValue  interface{}
}

func (systemGroup *SystemGroup) GetField(gid int64) (sysGroup SystemGroup, err error) {
	err = orm.Eloquent.Where(&SystemGroup{ID: gid}).Find(&sysGroup).Error
	return
}

/**
 * @Description: 通过configName 获取对应的 数据
 * @receiver systemGroup
 * @param configName
 * @return sysGroups
 * @return err
 */
func (systemGroup *SystemGroup) GetDataByConfigName(configName string) (sysGroups []SystemGroupData4Value, err error) {

	selectFields := h.SelectFieldsBuild(
		h.SelectFields{TableName: SystemGroup{}.TableName(), FieldList: []string{"id", "name", "info", "config_name"}},
		h.SelectFields{TableName: SystemGroupData{}.TableName(), FieldList: []string{"value"}},
	)
	joinStr := h.JoinTable(SystemGroup{}.TableName(), SystemGroupData{}.TableName(), "id", "gid", h.InnerJoin)

	err = orm.Eloquent.Model(&SystemGroup{}).Select(selectFields).Joins(joinStr).Where(&SystemGroup{ConfigName: configName}).Scan(&sysGroups).Error
	if err == nil && len(sysGroups) > 0 {
		for i := 0; i < len(sysGroups); i++ {
			_ = json.Unmarshal([]byte(sysGroups[i].Value), &sysGroups[i].DataValue)
			sysGroups[i].Value = ""
		}
	}
	return
}
