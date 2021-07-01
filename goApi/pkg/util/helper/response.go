package helper

import (
	"github.com/gin-gonic/gin"
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
	resp.ErrCode = enum.DefaultErrCode
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

func RespJson(ctx *gin.Context, srvResp ServiceResp) {
	if srvResp.Code == enum.DefaultSuccessCode {
		ctx.JSON(http.StatusOK, RespSuccess(srvResp.Message, srvResp.Data))
		return
	}
	ctx.JSON(http.StatusOK, RespError(srvResp.Message, srvResp.Code, srvResp.Data))
	return
}
