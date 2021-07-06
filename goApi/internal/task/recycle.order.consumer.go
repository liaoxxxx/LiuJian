package task

import (
	"encoding/json"
	"goApi/internal/models/entity"
	"goApi/internal/repository"
	"goApi/pkg/logger"
)

type recycleOrderConsumer struct{}

var RecycleOrderConsumer recycleOrderConsumer

func (recOrderConsumer *recycleOrderConsumer) Handle(delivery []byte) (err error) {
	orderRec := new(entity.OrderRecycle)
	err = json.Unmarshal(delivery, orderRec)
	if err != nil {
		logger.Logger.Warn("unmarshal to OrderRecycle err:" + err.Error())
		return err
	}
	insertedId, err := repository.OrderRecycleRepo.Create(*orderRec)
	if err != nil || insertedId == 0 {
		logger.Logger.Warn("OrderRecycle Create err")
		return err
	}
	return nil
}
