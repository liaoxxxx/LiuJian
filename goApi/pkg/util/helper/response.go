package helper

import (
	"goApi/pkg/enum"
	"net/http"
)

const (
	StatusSuccessString = "success"
	StatusErrorString   = "error"
)

type Response struct {
	Code    int64       `json:"code"`
	ErrCode string      `json:"errCode"`
	Status  string      `json:"status"`
	Empty   bool        `json:"empty"`
	Msg     string      `json:"msg"`
	Data    interface{} `json:"data"`
}

func RespSuccess(message string, data interface{}) Response {
	var resp Response
	resp.Code = http.StatusOK
	resp.ErrCode = "0"
	resp.Empty = false
	resp.Status = StatusSuccessString
	resp.Msg = message
	resp.Data = data
	return resp
}

func RespError(message string, errCode string, data interface{}) Response {
	var resp Response
	resp.Code = http.StatusBadRequest
	resp.ErrCode = errCode
	resp.Empty = true
	resp.Status = StatusErrorString
	resp.Msg = message
	resp.Data = data
	return resp
}

func RespDataEmpty(message string) Response {
	var resp Response
	resp.Code = http.StatusOK
	resp.ErrCode = enum.DefaultSuccessCode
	resp.Empty = true
	resp.Status = StatusErrorString
	resp.Msg = message
	return resp
}

func UserAuthFail() Response {
	empty := make(map[string]interface{}, 0)
	return RespError(enum.RequestAuthFailErrMsg, enum.RequestAuthFailErrCode, empty)
}

type ServiceResp struct {
	Code    string      `json:"code"`
	Message string      `json:"message"`
	Data    interface{} `json:"data"`
}
