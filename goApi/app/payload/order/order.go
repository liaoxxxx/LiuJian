package order

// Demo demo对象
type Creator struct {
	AddressId          int64
	IsPreengage        int8
	Mark               string
	Unique             string
	Phone              string `json:"phone" swaggo:"false,手机号"`
	PreengageTime      string
	RealName           string
	RecycleProductList []recycleProductList
}

type recycleProductList struct {
	Photos        []string
	weightCateId  int64
	weightCateStr string
}
