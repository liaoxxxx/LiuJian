package export

// RecyclerWorkStat  回收员的统计数据
type RecyclerWorkStat struct {
	CountOrder  int64   `json:"count_order"`  //完成订单数
	IncomeMoney float64 `json:"income_money"` //收入
}
