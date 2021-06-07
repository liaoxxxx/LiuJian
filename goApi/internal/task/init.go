package task

import (
	"fmt"
	"github.com/Shopify/sarama"
	"goApi/configs"
	"goApi/pkg/logger"
	"goApi/pkg/util"
)

func Init() {
	initTopic()
	ListenInit()
}

func initTopic() {
	/*topics, err := util.KafkaClient.Consumer.Topics()
	var taskTopicsTmp =configs.TaskTopicsList
	if err !=nil {
		logger.Logger.Info(fmt.Sprintf( "get mq topic err:%v",err.Error()))
	}
	logger.Logger.Info(fmt.Sprintf("exist topics is ：%v",topics))
	for tIndex, tmp := range taskTopicsTmp {
		for _, topic := range topics {
			if topic ==tmp {
				taskTopicsTmp=append(taskTopicsTmp[0:tIndex], taskTopicsTmp[tIndex+1:len(taskTopicsTmp)]...)
			}
		}
	}
	logger.Logger.Info(fmt.Sprintf("%v",taskTopicsTmp))
	*/
	err := util.KafkaClient.CreateTopics(configs.TaskTopicsList)

	if err != nil {
		logger.Logger.Warn(fmt.Sprintf("create mq topic fail err:%v", err.Error()))
	}
}

func ListenInit() {
	logger.Logger.Info(fmt.Sprintf("ListenInit TOPICS_ORDER_USER_ISSUE  ----------> "))
	orderIssueMsgList := listenTopic(configs.TOPICS_ORDER_USER_ISSUE)
	for i, message := range orderIssueMsgList {
		logger.Logger.Warn(fmt.Sprintf("TOPICS_ORDER_USER_ISSUE %v is: %v", i, *message))
	}
}

func listenTopic(topicName string) []*sarama.ConsumerMessage {
	err := util.KafkaClient.SubscribeMsg(topicName)
	if err != nil {
		logger.Logger.Info(fmt.Sprintf(" SubscribeMsg0 err:%v", err))
	}
	return nil
}
