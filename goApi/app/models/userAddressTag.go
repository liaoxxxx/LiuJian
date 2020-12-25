package models

type UserAddressTag struct {
	ID   int64  `gorm:"primaryKey;autoIncrement:true"`
	Uid  int64  `json:"uid"`  // 列名为 `username`
	Name string `json:"name"` // 列名为 `password`

}

func (UserAddressTag) TableName() string {
	return "eb_user_address_tag"
}
