package helper

import (
	"goApi/app/enum"
	"strconv"
)

func GetErrCode(appModule, processCode, businessCode, specificCode int64) int64 {
	codeStr := strconv.FormatInt(appModule, 10) + strconv.FormatInt(processCode, 10) + strconv.FormatInt(businessCode, 10) + strconv.FormatInt(specificCode, 10)
	return parseStr2Int(codeStr)
}

func GetErrMsg(appModule, processMsg, businessMsg, specificMsg string) string {
	msgStr := appModule + processMsg + businessMsg + specificMsg
	return msgStr
}

func GetUsrAErrCode(processCode, businessCode, specificCode int64) int64 {
	codeStr := GetErrCode(enum.AppUserCode, processCode, businessCode, specificCode)
	return codeStr
}

func GetUsrAErrMsg(processMsg, businessMsg, specificMsg string) string {
	return GetErrMsg(enum.AppUserMsg, processMsg, businessMsg, specificMsg)
}
