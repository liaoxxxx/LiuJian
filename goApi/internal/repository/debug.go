package repository

import (
	"goApi/internal/models/database"
	"goApi/internal/models/entity"
	"time"
)

type debugRepo struct {
}

var DebugLog debugRepo

//phone 单条数据
func (*debugRepo) InsertLog(logInfo string) (err error) {
	err = database.Eloquent.Create(&entity.DebugLog{Log: logInfo, CreateAt: time.Now()}).Error
	return
}
