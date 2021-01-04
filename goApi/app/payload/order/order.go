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
	/*RecordID  string    `json:"record_id" swaggo:"false,记录ID"`
	Code      string    `json:"code" binding:"required" swaggo:"true,编号"`
	Name      string    `json:"name" binding:"required" swaggo:"true,名称"`
	Memo      string    `json:"memo" swaggo:"false,备注"`
	Status    int       `json:"status" binding:"required,max=2,min=1" swaggo:"true,状态(1:启用 2:停用)"`
	Creator   string    `json:"creator" swaggo:"false,创建者"`
	CreatedAt time.Time `json:"created_at" swaggo:"false,创建时间"`*/
}

type recycleProductList struct {
	Photos        []string
	weightCateId  int64
	weightCateStr string
}
