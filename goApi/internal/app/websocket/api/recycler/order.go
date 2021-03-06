package recycler

import (
	"github.com/gin-gonic/gin"
	"github.com/gorilla/websocket"
	"goApi/internal/app/websocket/api"
	"goApi/internal/repository"
	"goApi/pkg/logger"
	"goApi/pkg/util/helper"
	"log"
)

type orderServer struct{}

var OrderServer orderServer

var OrderDistributeClients = make(map[int64]map[int64]*websocket.Conn)

func (*orderServer) OrderDistribute(ctx *gin.Context) {
	recerId := helper.GetRecIdByCtx(ctx)
	working, err := repository.RecyclerWorkingRepo.FindByUid(recerId)
	if err != nil {
		logger.Logger.Warn(err.Error())
		return
	}
	cityId := working.CityId
	wrt := ctx.Writer
	req := ctx.Request
	wsConn, err := api.Upgrader.Upgrade(wrt, req, nil)

	if err != nil {
		logger.Logger.Warn(err.Error())
		return
	}
	//存储起来
	cityMap := OrderDistributeClients[cityId]
	if cityMap == nil {
		OrderDistributeClients[cityId] = map[int64]*websocket.Conn{recerId: wsConn}
	} else {
		OrderDistributeClients[cityId][recerId] = wsConn
	}
	for {
		mt, message, err := wsConn.ReadMessage()
		if err != nil {
			log.Println("read:", err)
			break
		}
		log.Printf("recv: %s", message)
		err = wsConn.WriteMessage(mt, message)
		if err != nil {
			log.Println("write:", err)
			break
		}
	}
}
