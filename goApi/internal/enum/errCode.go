package enum

type Error struct {
}

//    A __  BB __  CC cc __  DD

//    A ：应用模块{1:用户应用,2:回收员应用}

//    BB ：流程	{
//   	1*：Rtr路由,
//  	2*: Mdv 中间件,
// 		3*: Ctl 控制器,
//		4*: Svc 服务,
//		5*: Rpstr 数据库|缓存
//   }

//	  CC__cc ：业务类型:{
//	 		CC_10： User 用户模块：  [cc_01:登陆, cc_02:注册 ],
//	 		CC_20: UAddr 用户地址模块：  [cc_01:列表, cc_02:单个,cc_03:添加  ],
//		}

// 	  DD ：具体的错误类型
const (
	/*应用模块*/
	AppUserCode       = 1
	AppUserMsg        = "api-用戶端 "
	AppRecycleManCode = 2
	AppRecycleManMsg  = "api-回收员端 "

	/*流程*/
	ProcessRouteCode = 01
	ProcessRouteMsg  = "流程-路由 "

	ProcessMiddlewareCode = 02
	ProcessMiddlewareMsg  = "流程-中间件 "

	ProcessControllerCode = 03
	ProcessControllerMsg  = "流程-控制器 "

	ProcessServiceCode = 04
	ProcessServiceMsg  = "流程-服务 "

	ProcessRepositoryCode = 05
	ProcessRepositoryMsg  = "流程-存储 "

	/*  业务类型  */
	BusinessUserCode        = 11
	BusinessUserMsg         = "业务-用户 "
	BusinessUserAddressCode = 12
	BusinessUserAddressMsg  = "业务-用户地址 "

	BusinessOrderCode = 21
	BusinessOrderMsg  = "业务-订单 "

	/*具体的错误类型 */
	SpecificErrorInsertCode       = 01
	SpecificErrorInsertMsg        = "错误： 新增数据失败"
	SpecificErrorUpdateCode       = 02
	SpecificErrorUpdateMsg        = "错误： 更新数据失败"
	SpecificErrorDeleteCode       = 03
	SpecificErrorDeleteMsg        = "错误： 删除数据失败"
	SpecificErrorFindCode         = 04
	SpecificErrorFindMsg          = "错误： 查找数据失败"
	SpecificErrorDataNotFoundCode = 06
	SpecificErrorDataNotFoundMsg  = "找不到数据"

	SpecificErrorParamUndefinedCode = 05
	SpecificErrorParamUndefinedMsg  = "错误： 参数丢失"
)
