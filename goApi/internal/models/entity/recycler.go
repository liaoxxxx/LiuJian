package entity

import (
	"gorm.io/gorm"
)

type Recycler struct {
	ID       int64   `gorm:"primaryKey;autoIncrement:true"`
	Username string  `json:"username"` // 列名为 `username`
	Pwd      string  `json:"-"`        // 列名为 `password`
	Phone    string  `json:"phone"`
	Salt     string  `json:"-"`
	RealName string  `json:"real_name"`
	CardId   int64   `json:"card_id"`
	Avatar   float64 `json:"recycle_weight"`
	gorm.Model
}

func (*Recycler) TableName() string {
	return "eb_recycler"
}
