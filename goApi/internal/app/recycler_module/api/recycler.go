package api

import (
	"github.com/gin-gonic/gin"
	pld "goApi/internal/app/recycler_module/payload"
	"goApi/internal/app/recycler_module/service"
	"goApi/pkg/util/helper"
	"net/http"
)

type recyclerServer struct{}

var MapServer = new(mapServer)

func (*mapServer) PathPlanning(c *gin.Context) {
	var pathPlanningPld pld.PathPlanning
	helper.BindQuery(c, &pathPlanningPld)
	resp := service.MapService.PathPlanning(pathPlanningPld)
	c.JSON(http.StatusOK, resp)
}
