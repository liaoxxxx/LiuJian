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
	AppUserCode   = 1
	AppRecycleManCode = 2

	AppUserMsg       = "app-用戶端 "
	AppRecycleManMsg = "app-用戶端 "





	/*流程*/
	ProcessRouteCode      = 01
	ProcessMiddlewareCode = 02
	ProcessControllerCode = 03
	ProcessServiceCode    = 04
	ProcessRepositoryCode = 05

	ProcessRouteMsg      = "流程-路由 "
	ProcessMiddlewareMsg = "流程-中间件 "
	ProcessControllerMsg = "流程-控制器 "
	ProcessServiceMsg    = "流程-服务 "
	ProcessRepositoryMsg = "流程-存储 "





	/*  业务类型  */
	BusinessUserCode        = 11
	BusinessUserAddressCode = 12

	BusinessOrderCode = 21


	BusinessUserMsg        = "业务-用户 "
	BusinessUserAddressMsg = "业务-用户地址 "

	BusinessOrderMsg = "业务-订单 "




	/*具体的错误类型 */
	SpecificErrorInsertCode = 01
	SpecificErrorUpdateCode = 02
	SpecificErrorDeleteCode = 03
	SpecificErrorFindCode   = 04

	SpecificErrorParamUndefinedCode = 05

	SpecificErrorInsertMsg = "错误： 新增数据失败"
	SpecificErrorUpdateMsg = "错误： 更新数据失败"
	SpecificErrorDeleteMsg = "错误： 删除数据失败"
	SpecificErrorFindMsg   = "错误： 查找数据失败"

	SpecificErrorParamUndefinedMsg = "错误： 参数丢失"
)
