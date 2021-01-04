package enum

type Error struct {
}

const (
	ParamUndefinedCode = 100001
	ParamUndefinedMsg  = "请求参数未定义"

	OrderExistedCode = 120001
	OrderExistedMsg  = "订单已经存在，请勿重复提交"
)
