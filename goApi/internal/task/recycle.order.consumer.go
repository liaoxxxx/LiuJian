package task

import (
	"encoding/json"
	"github.com/streadway/amqp"
	"goApi/internal/models/entity"
	"goApi/internal/repository"
	"goApi/pkg/logger"
)

type recycleOrderConsumer struct{}

var RecycleOrderConsumer recycleOrderConsumer

func (recOrderConsumer *recycleOrderConsumer) Handle(delivery amqp.Delivery) (err error) {
	orderRec := new(entity.OrderRecycle)
	err = json.Unmarshal(delivery.Body, orderRec)
	if err != nil {
		logger.Logger.Warn("unmarshal to OrderRecycle err:" + err.Error())
		return err
	}
	if (*orderRec).OrderId == 0 {
		logger.Logger.Warn("OrderId Is Not Set")
		return err
	}
	insertedId, err := repository.OrderRecycleRepo.Create(*orderRec)
	if err != nil || insertedId == 0 {
		logger.Logger.Warn("OrderRecycle Create err")
		return err
	}
	return nil
}
