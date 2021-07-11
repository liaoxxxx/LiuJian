package entity

import "time"

type DebugLog struct {
	ID       int64     `gorm:"primaryKey;autoIncrement:true" json:"id"`
	CreateAt time.Time `json:"create_at"`
	Log      string    `json:"log"`
}

func (DebugLog) TableName() string {
	return "eb_debug_log"
}
