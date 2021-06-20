package entity

type Order struct {
	ID            int64   `gorm:"primaryKey;autoIncrement:true" json:"id"`
	OrderId       string  `json:"order_id"`
	Uid           int64   `json:"uid"`
	RealName      string  `json:"real_name"`
	UserPhone     string  `json:"user_phone"`
	UserAddress   string  `json:"user_address"`
	UserAddressId int64   `json:"user_address_id"`
	TotalNum      int64   `json:"total_num"`
	TotalPrice    float64 `json:"total_price"`
	GainIntegral  float64 `json:"gain_integral"`
	PayPrice      float64 `json:"pay_price"`

	PayType int8    `json:"pay_type"`
	Status  float64 `json:"status"`
	Mark    string  `json:"mark"`
	Unique  string  `json:"unique"`
	Remark  float64 `json:"remark"`
	MerId   int64   `json:"mer_id"`
	SiteId  int64   `json:"site_id"`

	PreengageTime int64 `json:"preengage_time"`
	PayTime       int64 `json:"pay_time"`
	AddTime       int64 `json:"add_time"`

	IsChannel   int8    `json:"is_channel"`
	IsDel       float64 `json:"is_del"`
	IsRemind    int8    `json:"is_remind"`
	IsSystemDel int8    `json:"is_system_del"`
	IsPreengage int8    `json:"is_preengage"`
}

func (Order) TableName() string {
	return "eb_order"
}
