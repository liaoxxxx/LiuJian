package enum

const (
	RedEnvelopTypeSingle      = 10 //红包类型  单人
	RedEnvelopTypeSingleGroup = 11 //红包类型  单个群内红包
	RedEnvelopTypeMultiSame   = 20 //红包类型  群发等额
	RedEnvelopTypeMultiRand   = 21 //红包类型  群发随机

)

var RedEnvelopTypeMap = map[int]string{
	RedEnvelopTypeSingle:      "普通红包",
	RedEnvelopTypeSingleGroup: "群内普通红包",
	RedEnvelopTypeMultiSame:   "群发等额红包",
	RedEnvelopTypeMultiRand:   "群发随机红包",
}
