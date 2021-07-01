package user

type UAddressAdd struct {
	Id        int64  `form:"id" json:"id"   `
	City      string `form:"city" json:"city"   `
	Detail    string `form:"detail" json:"detail"   `
	District  string `form:"district" json:"district"   `
	IsDefault int64  `form:"is_default" json:"is_default"   `
	Phone     string `form:"phone" json:"phone"   `
	PostCode  string `form:"post_code" json:"post_code"   `
	Province  string `form:"province" json:"province"   `
	RealName  string `form:"real_name" json:"real_name"   `
}
