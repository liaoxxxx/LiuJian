package task

import (
	"fmt"
	"goApi/configs"
	"goApi/pkg/logger"
	"goApi/pkg/util"
)

func Init() {
	initTopic()
	ListenInit()
}

var ChanOrderUserIssue chan string

/**
 * @Description:  初始化必要的 topic
 */
func initTopic() {
	logger.Logger.Info(fmt.Sprintf("init topic start -----------"))
	err := util.KafkaClient.CreateTopics(configs.TaskTopicsList)

	if err != nil {
		logger.Logger.Warn(fmt.Sprintf("create mq topic fail err:%v", err.Error()))
	}
}

func ListenInit() {
	logger.Logger.Info(fmt.Sprintf("listen topic start -----------"))
	go func() {
		for {
			logger.Logger.Info(fmt.Sprintf("ListenInit TOPICS_ORDER_USER_ISSUE  ----------> "))
			err := listenTopic(configs.TOPICS_ORDER_USER_ISSUE, &ChanOrderUserIssue)
			if err != nil {
				logger.Logger.Warn(fmt.Sprintf("topic get message err:%v ", err))
			}
			for issueMsg := range ChanOrderUserIssue {
				logger.Logger.Warn(fmt.Sprintf("TOPICS_ORDER_USER_ISSUE get message and content is: %v", issueMsg))
			}
		}
	}()

}

func listenTopic(topicName string, taskChannel *chan string) error {
	err := util.KafkaClient.SubscribeMsg(topicName, taskChannel)
	if err != nil {
		return err
		logger.Logger.Info(fmt.Sprintf(" SubscribeMsg0 err:%v", err))
	}
	return nil
}
