package database

import (
	"fmt"
	"gorm.io/driver/mysql"
	"gorm.io/gorm"
	"gorm.io/gorm/logger"
	"time"
)

var Eloquent *gorm.DB

func init() {
	var err error

	db, err := gorm.Open(mysql.New(mysql.Config{
		DSN:                       "lj_recycling:zHpeFc8j4RXtbXK8@tcp(47.115.182.67:3306)/lj_recycling?charset=utf8&parseTime=true&loc=Local", // DSN data source name
		DefaultStringSize:         256,                                                                                                        // string 类型字段的默认长度
		DisableDatetimePrecision:  true,                                                                                                       // 禁用 datetime 精度，MySQL 5.6 之前的数据库不支持
		DontSupportRenameIndex:    true,                                                                                                       // 重命名索引时采用删除并新建的方式，MySQL 5.7 之前的数据库和 MariaDB 不支持重命名索引
		DontSupportRenameColumn:   true,                                                                                                       // 用 `change` 重命名列，MySQL 8 之前的数据库和 MariaDB 不支持重命名列
		SkipInitializeWithVersion: false,                                                                                                      // 根据当前 MySQL 版本自动配置
	}), &gorm.Config{
		Logger: logger.Default.LogMode(logger.Info), //将会打印sql
	})

	if err != nil {
		fmt.Printf("mysql connect error %v", err)
	}

	if db.Error != nil {
		fmt.Printf("database error %v", db.Error)
	}

	sqlDB, err := db.DB()
	if sqlDB != nil {
		//############### 设置连接池 ###############
		// SetMaxIdleConns sets the maximum number of connections in the idle connection pool.
		sqlDB.SetMaxIdleConns(10)

		// SetMaxOpenConns sets the maximum number of open connections to the database.
		sqlDB.SetMaxOpenConns(100)

		// SetConnMaxLifetime sets the maximum amount of time a connection may be reused.
		sqlDB.SetConnMaxLifetime(time.Hour)
		Eloquent = db

	}

}
