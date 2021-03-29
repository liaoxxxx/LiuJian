package order

// 創建回收订单的RequestPayload demo对象
type Creator struct {
	AddressId          int64
	IsPreengage        int8
	Mark               string
	Unique             string
	Phone              string `json:"phone" swaggo:"false,手机号"`
	PreengageTime      string
	RealName           string
	RecycleProductList []RecycleProductList
}

/**
 */
type RecycleProductList struct {
	Photos        []string
	WeightCateId  int64
	WeightCateStr string
	RecCateStr    string
	RecCateId     int64
}
