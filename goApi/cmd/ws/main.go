package main

import (
	"goApi/configs"
	"goApi/internal/task"
	"goApi/pkg/logger"
	"goApi/pkg/util"
	"goApi/router"
)

func main() {

	go func() {
		brookedChan := make(chan bool, 1)
		logger.Logger.Warn("listen topic  TOPICS_ORDER_RECYCLER_PUBLISH start")
		err := util.RabbitMQClient.Received(configs.TOPICS_ORDER_RECYCLER_PUBLISH, task.RecycleOrderConsumer.HandlePublish)
		if err != nil {
			logger.Logger.Warn("listen topic  TOPICS_ORDER_RECYCLER_PUBLISH failed:" + err.Error())
		}
		logger.Logger.Warn("listen topic  TOPICS_ORDER_RECYCLER_PUBLISH success  ,continue")
		<-brookedChan
	}()
	routerWs := router.InitWsRouter()
	routerWs.Run(configs.WebsocketPort)
}
