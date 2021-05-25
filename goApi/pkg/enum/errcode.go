package enum

const (
	DefaultSuccessCode = "0"

	DefaultErrCode = "10 00 01"
	DefaultErrMsg  = "内部错误"

	//#############  请求错误    #############
	DefaultRequestErrCode = "20 00 01"
	DefaultRequestErrMsg  = "请求错误"

	RequestAuthFailErrCode = "20 01 01"
	RequestAuthFailErrMsg  = "用户信息丢失，鉴权失败"

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

	PhoneNotRegisterErrCode = "990002"
	PhoneNotRegisterErrMsg  = "手机号未注册"

	JsonUnmarshalErrCode = "990003"
	JsonUnmarshalErrMsg  = "json解析失败："

	RequestParamUnexpectErrCode = "990004"
	RequestParamUnexpectErrMsg  = "不符合预期的输入参数"
)
