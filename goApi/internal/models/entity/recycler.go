package entity

import (
	"gorm.io/gorm"
)

type Recycler struct {
	ID       int64  `gorm:"primaryKey;autoIncrement:true" json:"id"`
	Nickname string `json:"nickname"` // 列名为 `username`
	Pwd      string `json:"-"`        // 列名为 `password`
	Phone    string `json:"phone"`
	Salt     string `json:"-" gorm:"salt"`
	RealName string `json:"real_name"`
	CardId   int64  `json:"card_id"`
	Avatar   string `json:"avatar"`
	gorm.Model
}

func (*Recycler) TableName() string {
	return "eb_recycler"
}
