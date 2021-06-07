package util

import (
	"fmt"
	"github.com/Shopify/sarama"
	"goApi/configs"
	"goApi/pkg/logger"
	"log"
	"time"
)

type kafkaClient struct {
	Consumer sarama.Consumer     //消费
	Producer sarama.SyncProducer //生产
	Broker   *sarama.Broker
}

var KafkaClient kafkaClient

var kafKaSource = fmt.Sprintf("%v:%v", configs.SERVER_IP, configs.KAFKA_PORT)

func KafkaInit() {
	{
		// Set broker configuration
		broker := sarama.NewBroker(kafKaSource)
		// Additional configurations. Check sarama doc for more info
		config := sarama.NewConfig()
		config.Version = sarama.V2_8_0_0
		// Open broker connection with configs defined above
		err := broker.Open(config)
		if err != nil {
			return
		}
		// check if the connection was OK
		logger.Logger.Info("start to connect kafka broker")
		connBool, err := broker.Connected()
		if err != nil {
			logger.Logger.Info(fmt.Sprintf("kafka's broker connect err: %v", err.Error()))
		} else {
			KafkaClient.Broker = broker
			logger.Logger.Info(fmt.Sprintf("kafka's brokerrrr connect success : %v", connBool))
		}
	}

	{
		// 构建 生产者
		// 生成 生产者配置文件
		config := sarama.NewConfig()
		// 设置生产者 消息 回复等级 0 1 all
		config.Producer.RequiredAcks = sarama.WaitForAll
		// 设置生产者 成功 发送消息 将在什么 通道返回
		config.Producer.Return.Successes = true
		// 设置生产者 发送的分区
		config.Producer.Partitioner = sarama.NewRandomPartitioner
		// 连接 kafka
		producer, err := sarama.NewSyncProducer([]string{kafKaSource}, config)
		if err != nil {
			log.Print(err)
			return
		}
		defer func(producer sarama.SyncProducer) {
			err := producer.Close()
			if err != nil {

			}
		}(producer)
		KafkaClient.Producer = producer
	}

	{
		logger.Logger.Info("start to connect kafka server")
		//consumer, err := sarama.NewConsumer([]string{ fmt.Sprintf("%v:%v",configs.SERVER_IP,configs.KAFKA_PORT)}, nil)

		consumer, err := sarama.NewConsumer([]string{kafKaSource}, nil)
		if err != nil {
			logger.Logger.Info(fmt.Sprintf("fail to connnetc server:%v , err:%v", kafKaSource, err))
			return
		} else {
			logger.Logger.Info(fmt.Sprintf("kafkaClient's consumer connet to source:%v ", kafKaSource))
		}
		KafkaClient.Consumer = consumer
	}

}

func (client *kafkaClient) CreateTopics(topicNames []string) error {

	topicDetails := make(map[string]*sarama.TopicDetail)
	for _, topicName := range topicNames {
		topicDetail := &sarama.TopicDetail{}
		topicDetail.NumPartitions = int32(1)
		topicDetail.ReplicationFactor = int16(1)
		topicDetail.ConfigEntries = make(map[string]*string)
		topicDetails[topicName] = topicDetail
	}

	request := sarama.CreateTopicsRequest{
		Timeout:      time.Second * 15,
		TopicDetails: topicDetails,
	}
	// Send request to Broker
	response, err := client.Broker.CreateTopics(&request)
	// handle errors if any
	if err != nil {
		logger.Logger.Info(fmt.Sprintf("Create Topics err: %v", err.Error()))
		return err
	}
	_ = response.TopicErrors

	return nil
}

func (client *kafkaClient) ProduceMsg(msgContent string, topicName string) error {
	// 构建 消息
	msg := &sarama.ProducerMessage{}
	msg.Topic = topicName
	msg.Value = sarama.StringEncoder(msgContent)
	// 发送消息
	message, offset, err := client.Producer.SendMessage(msg)
	if err != nil {
		logger.Logger.Info(fmt.Sprintf("ProduceMsg to topic [%v] err :%v", topicName, err.Error()))
		return err
	}
	fmt.Println(message, " ", offset)
	return nil
}

func (client *kafkaClient) SubscribeMsg(topicName string) []*sarama.ConsumerMessage {
	logger.Logger.Info(fmt.Sprintf("SubscribeMsg Start  topic name is:%v ---------------------", topicName))

	var messageListTemp []*sarama.ConsumerMessage
	partitionList, err := client.Consumer.Partitions(topicName) // 根据topic取到所有的分区
	logger.Logger.Info(fmt.Sprintf("partitionList length :%v", len(partitionList)))
	if err != nil {
		fmt.Printf("fail to get list of partition err:%v", err)
		return nil
	}
	for partition := range partitionList { // 遍历所有的分区
		var messageList []*sarama.ConsumerMessage
		// 针对每个分区创建一个对应的分区消费者
		pc, err := client.Consumer.ConsumePartition(topicName, int32(partition), sarama.OffsetNewest)
		if err != nil {
			fmt.Printf("failed to start consumer for partition %d,err:%v", partition, err)
			return nil
		}
		//defer pc.AsyncClose()
		// 异步从每个分区消费信息
		go func(sarama.PartitionConsumer) {
			for msg := range pc.Messages() {
				messageList = append(messageList, msg)
				fmt.Printf("Partition:%d Offset:%d Key:%v Value:%v", msg.Partition, msg.Offset, msg.Key, msg.Value)
			}
			messageListTemp = messageList
		}(pc)
	}
	return messageListTemp
}
