package user

type UAddressAdd struct {
	Id        int64  `form:"id" json:"id"   `
	City      string `form:"city" json:"city"   binding:"required"`
	Detail    string `form:"detail" json:"detail"   binding:"required"`
	District  string `form:"district" json:"district"   binding:"required"`
	IsDefault int8   `form:"is_default" json:"is_default"   binding:"required"`
	Phone     string `form:"phone" json:"phone"   binding:"required"`
	PostCode  string `form:"post_code" json:"post_code"   binding:"required"`
	Province  string `form:"province" json:"province"   binding:"required"`
	RealName  string `form:"real_name" json:"real_name"   binding:"required"`
}
