package entity

import "time"

type OrderRecycle struct {
	ID            int64  `gorm:"primaryKey;autoIncrement:true" json:"id"`
	OrderId       int64  `json:"order_id"`
	Unique        string `json:"unique"`
	UserId        int64  `json:"user_id"`
	RecyclerId    int64  `json:"recycler_id"` //回收员
	UserAddress   string `json:"user_address"`
	UserAddressId int64  `json:"user_address_id"`

	StartLat       float64 `json:"start_lat"`       //起点纬度
	StartLng       float64 `json:"start_lng"`       //起点经度
	EndLat         float64 `json:"end_lat"`         //终点纬度
	EndLng         float64 `json:"end_lng"`         //终点经度
	LinearDistance float64 `json:"linear_distance"` //直线距离，千米
	RouteDistance  float64 `json:"route_distance"`  //路线距离

	RecycleAmount float64 `json:"recycle_amount"` //回收费用
	RecycleStatus int8    `json:"recycle_status"` //回收状态码
	SiteId        int64   `json:"site_id"`        // 站点
	AgentId       int64   `json:"agent_id"`       //代理

	IsWithdrawFinish int8      `json:"is_withdraw_finish"` //是否已经完成提现
	IsWithdrawApply  int8      `json:"is_withdraw_apply"`  //正在处于申请提现状态
	IsDelete         int8      `json:"is_delete"`          //是否删除
	IsSystemDel      int8      `json:"is_system_del"`
	SystemConfirm    int8      `json:"system_confirm"` //系统后台确认配送完成  0  false ;1 true,
	CreateAt         time.Time `json:"create_at"`
	UpdateAt         time.Time `json:"update_at"`
}

func (OrderRecycle) TableName() string {
	return "eb_order_recycle"
}
