package task

import (
	"encoding/json"
	"fmt"
	"goApi/configs"
	"goApi/internal/app/websocket/api/recycler"
	"goApi/internal/models/entity"
	"goApi/internal/repository"
	"goApi/pkg/logger"
	"goApi/pkg/util"
)

type recycleOrderConsumer struct{}

var RecycleOrderConsumer recycleOrderConsumer

func (recOrderConsumer *recycleOrderConsumer) Handle(delivery []byte) (err error) {
	repository.DebugLog.InsertLog("RecycleOrderConsumer Handle get one row of  orderRec")
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
	//发送到队列
	orderRec.ID = insertedId
	orderRecJson, err := json.Marshal(orderRec)
	if err != nil {
		logger.Logger.Warn("OrderRecycle Marshal err")
		return err
	}
	_ = util.RabbitMQClient.Publish(configs.TOPICS_ORDER_RECYCLER_PUBLISH, orderRecJson)
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
	repository.DebugLog.InsertLog("HandlePublish get one Row of  orderRec:")
	var recyclerIdList []int64
	err = json.Unmarshal(delivery, orderRec)
	if err != nil {
		repository.DebugLog.InsertLog("unmarshal to OrderRecycle err:" + err.Error())
		return err
	}
	recyclerClientList := recycler.OrderDistributeClients[orderRec.CityId]
	repository.DebugLog.InsertLog(fmt.Sprintf("CityId is %v  |  recyclerClientList.len : %v", orderRec.CityId, len(recyclerClientList)))
	for recerId, client := range recyclerClientList {
		repository.DebugLog.InsertLog(fmt.Sprintf("recerId is %v  |", recerId))
		recyclerIdList = append(recyclerIdList, recerId)
		err := client.WriteJSON(orderRec)
		if err != nil {
			repository.DebugLog.InsertLog(fmt.Sprintf("send rec_order to user-%v err:%v", recerId, err.Error()))
			return err
		}
	}

	return nil
}
