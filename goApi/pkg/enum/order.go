package enum

const (
	OrderTypeRecyclePreShort = "rec" //回收订单的前缀

	OrderMainExistCode = "300101"
	OrderMainExistMsg  = "订单已经存在，创建失败！"

	OrderMainCreateCode = "300102"
	OrderMainCreateMsg  = "订单创建失败！"

	OrderExtInfoTypeUserPreCommit     = 10 //回收订单额外信息 用户预先提交
	OrderExtInfoTypeRecycleManConfirm = 20 //回收订单额外信息 回收员预确认
)
