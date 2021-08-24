package api

import "github.com/gin-gonic/gin"

type uploadServer struct {
}

var UploadServer uploadServer

// Single
//  @Description:  单文件上传
//  @receiver uploadServer
//  @param ctx
//
func (uploadServer) Single(ctx *gin.Context) {

}

//
//  Multi
//  @Description:  多文件上传
//  @receiver uploadServer
//  @param ctx
//
func (uploadServer) Multi(ctx *gin.Context) {

}
