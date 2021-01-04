package helper

import (
	"net/http"
)

const (
	StatusSuccessString = "success"
	StatusErrorString   = "error"
)

type Response struct {
	Code    int16
	ErrCode int64
	Status  string
	Msg     string
	Data    interface{}
}

func RespSuccess(message string, data interface{}) Response {
	var resp Response
	resp.Code = http.StatusOK
	resp.ErrCode = 0
	resp.Status = StatusSuccessString
	resp.Msg = message
	resp.Data = data
	return resp
}

func RespError(message string, errCode int64, data interface{}) Response {
	var resp Response
	resp.Code = http.StatusOK
	resp.ErrCode = errCode
	resp.Status = StatusErrorString
	resp.Msg = message
	resp.Data = data
	return resp
}
