package main

import (
	"fmt"
	"goApi/configs"
	"goApi/internal/task"
	"goApi/pkg/logger"
	"goApi/pkg/util"
)

func main() {
	err := util.RabbitMQClient.Received(configs.TOPICS_ORDER_USER_ISSUE, task.RecycleOrderConsumer.Handle)
	if err != nil {
		logger.Logger.Warn(fmt.Sprintf("Received  topic %v message err: %v", configs.TOPICS_ORDER_USER_ISSUE, err.Error()))
	}
}
