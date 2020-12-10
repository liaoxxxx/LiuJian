package database

import (
	"fmt"
	"gorm.io/driver/mysql"
	"gorm.io/gorm"
	"gorm.io/gorm/logger"
)

var Eloquent *gorm.DB

func init() {
	var err error

	db, err := gorm.Open(mysql.New(mysql.Config{
		DSN:                       "lj_recycling:fMNss3antjMTFWFD@tcp(111.229.128.239:3306)/lj_recycling?charset=utf8&parseTime=True&loc=Local", // DSN data source name
		DefaultStringSize:         256,                                                                                                          // string 类型字段的默认长度
		DisableDatetimePrecision:  true,                                                                                                         // 禁用 datetime 精度，MySQL 5.6 之前的数据库不支持
		DontSupportRenameIndex:    true,                                                                                                         // 重命名索引时采用删除并新建的方式，MySQL 5.7 之前的数据库和 MariaDB 不支持重命名索引
		DontSupportRenameColumn:   true,                                                                                                         // 用 `change` 重命名列，MySQL 8 之前的数据库和 MariaDB 不支持重命名列
		SkipInitializeWithVersion: false,                                                                                                        // 根据当前 MySQL 版本自动配置
	}), &gorm.Config{
		Logger: logger.Default.LogMode(logger.Info), //将会打印sql
	})

	if err != nil {
		fmt.Printf("mysql connect error %v", err)
	}

	if db.Error != nil {
		fmt.Printf("database error %v", db.Error)
	}

	Eloquent = db
}