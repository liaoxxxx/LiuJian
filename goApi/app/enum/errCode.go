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
	
	APP_USER_CODE=1
	APP_RECYCLE_MAN_=2


	PROCESS_ROUTE_CODE=01
	PROCESS_MIDDLEWARE_CODE=01
	PROCESS_CONTROLLER_CODE=01
	PROCESS_SERVICE_CODE=01
	PROCESS_REPOSTORY_CODE=01

	BUSINESS_PRE_USER_CODE=11
	BUSINESS_SFX_USER_CODE=11


	BUSINESS_PRE_USERADDRESS_CODE=12

	BUSINESS_PRE_ORDER_CODE=21

	
	
	
	ParamUndefinedCode = 100001
	ParamUndefinedMsg  = "请求参数未定义"

	OrderExistedCode = 150001
	OrderExistedMsg  = "订单已经存在，请勿重复提交"


)


