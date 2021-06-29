package enum

const (
	DefaultSuccessCode = "0"
	DefaultSuccessMsg  = "请求成功"

	DefaultErrCode = "10 00 01"
	DefaultErrMsg  = "内部错误"

	//#############  请求错误    #############
	DefaultRequestErrCode = "20 00 01"
	DefaultRequestErrMsg  = "请求错误"

	//#############  验证错误    #############
	SmsCodeCheckErrCode = "30 00 01"
	SmsCodeCheckErrMsg  = "短信验证码错误"

	//############# 数据存储错误    #############
	DatabaseDefaultErrCode = "40 00 01"
	DatabaseDefaultRrrMsg  = "数据存储出错"

	DatabaseUpdateErrCode = "40 00 02"
	DatabaseUpdateErrMsg  = "数据更新错误"

	DatabaseFindErrCode = 400003
	DatabaseFindErrMsg  = "数据获取错误"

	//############# 具体错误    #############
	PasswordAuthErrCode = "99 00 01"
	PasswordAuthErrMsg  = "登陆密码错误"

	JsonUnmarshalErrCode = "990003"
	JsonUnmarshalErrMsg  = "json解析失败："

	RequestParamUnexpectErrCode = "990004"
	RequestParamUnexpectErrMsg  = "不符合预期的输入参数"

	/*应用模块*/
	AppUserCode       = 1
	AppUserMsg        = "app-用戶端 "
	AppRecycleManCode = 2
	AppRecycleManMsg  = "app-回收员端 "

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
