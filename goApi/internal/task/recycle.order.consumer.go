package task

import (
	"encoding/json"
	"fmt"
	"goApi/configs"
	"goApi/internal/app/websocket/api/recycler"
	"goApi/internal/models/entity"
	"goApi/internal/repository"
	"goApi/pkg/enum"
	"goApi/pkg/logger"
	"goApi/pkg/util"
)

type recycleOrderConsumer struct{}

var RecycleOrderConsumer recycleOrderConsumer

func (recOrderConsumer *recycleOrderConsumer) Handle(delivery []byte) (err error) {
	logger.Logger.Info("RecycleOrderConsumer Handle get one row of  orderRec")
	err = util.RabbitMQClient.Publish(configs.TOPICS_ORDER_RECYCLER_PUBLISH, delivery)
	if err != nil {
		logger.Logger.Warn("Publish  to topic configs.TOPICS_ORDER_RECYCLER_PUBLISH err:" + err.Error())
	}
	orderRec := new(entity.OrderRecycle)
	err = json.Unmarshal(delivery, orderRec)
	if err != nil {
		logger.Logger.Warn("unmarshal to OrderRecycle err:" + err.Error())
		return err
	}
	insertedId, err := repository.OrderRecycleRepo.Create(*orderRec)
	if err != nil {
		logger.Logger.Warn(fmt.Sprintf("OrderRecycle Create err:%v", err.Error()))
		return err
	}
	if insertedId == 0 {
		logger.Logger.Warn(fmt.Sprintf("OrderRecycle Create insertedId:%v", insertedId))
		return fmt.Errorf("order_recycler insert is id no exist")
	}

	logger.Logger.Warn("OrderRecycle Create success")
	logger.Logger.Warn(fmt.Sprintf("OrderRecycle Create success & insertedId:%v", insertedId))
	//发送到队列
	orderRec.ID = insertedId
	//orderRecJson, err := json.Marshal(orderRec)
	if err != nil {
		logger.Logger.Warn("OrderRecycle Marshal err")
		return err
	}
	//err = util.RabbitMQClient.Publish(configs.TOPICS_ORDER_RECYCLER_PUBLISH, orderRecJson)
	if err != nil {
		logger.Logger.Warn("Publish  to topic configs.TOPICS_ORDER_RECYCLER_PUBLISH err:" + err.Error())
	}
	logger.Logger.Warn("Publish  to topic configs.TOPICS_ORDER_RECYCLER_PUBLISH successsssss")
	logger.Logger.Warn("————————————————————————————————————————————————————————————————————")

	return nil
}

// HandlePublish
/**
 * @Description:  监听 Order_recycler 创建完成 处理
 * @receiver recOrderConsumer
 * @param delivery
 * @return err
 */
func (recOrderConsumer *recycleOrderConsumer) HandlePublish(delivery []byte) (err error) {
	orderRec := new(entity.OrderRecycle)
	logger.Logger.Info("HandlePublish get one Row of  orderRec:")
	var recyclerIdList []int64
	err = json.Unmarshal(delivery, orderRec)
	if err != nil {
		logger.Logger.Info("unmarshal to OrderRecycle err:" + err.Error())
		return err
	}
	recyclerClientList := recycler.OrderDistributeClients[orderRec.CityId]
	logger.Logger.Info(fmt.Sprintf("CityId is %v  |  recyclerClientList.len : %v", orderRec.CityId, len(recyclerClientList)))
	for recerId, client := range recyclerClientList {
		logger.Logger.Info(fmt.Sprintf("recerId is %v  |", recerId))
		recyclerIdList = append(recyclerIdList, recerId)
		writeMap := map[string]interface{}{
			"option": enum.RecDistributeOptionPublishNotify,
			"order":  orderRec,
		}
		err := client.WriteJSON(writeMap)
		if err != nil {
			//发送失败就删除客户端的连接
			logger.Logger.Info(fmt.Sprintf("send rec_order to user-%v err:%v", recerId, err.Error()))
			delete(recycler.OrderDistributeClients[orderRec.CityId], recerId)
			return err
		}
	}

	return nil
}
