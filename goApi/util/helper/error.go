package helper

import "strconv"

func GetErrCode(appModule, processCode, businessCode, specificCode int64) int64 {
	codeStr := strconv.FormatInt(appModule, 10) + strconv.FormatInt(processCode, 10) + strconv.FormatInt(businessCode, 10) + strconv.FormatInt(specificCode, 10)
	return parseStr2Int(codeStr)
}

func GetErrMsg(appModule, processMsg, businessMsg, specificMsg string) string {
	msgStr := appModule + processMsg + businessMsg + specificMsg
	return msgStr
}
