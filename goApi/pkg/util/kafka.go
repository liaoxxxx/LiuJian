package util

import (
	"context"
	"fmt"
	"github.com/Shopify/sarama"
	"goApi/configs"
	"goApi/pkg/logger"
	"time"
)

type kafkaClient struct {
	ConsumerGroup sarama.ConsumerGroup //消费
	Producer      sarama.SyncProducer  //生产
	Broker        *sarama.Broker
	Client        sarama.Client //客户端
	Config        KafkaConfig   //配置项
}

type KafkaConfig struct {
	Address []string
	Configs *sarama.Config
}

var KafkaClient kafkaClient

var kafKaSource = fmt.Sprintf("%v:%v", configs.SERVER_IP, configs.KAFKA_PORT)

func KafkaInit() {

	initConfig()   //初始化配置
	initClient()   //初始化客户端
	initBroker()   //初始化broker
	initProducer() //初始化producer
	initConsumer() //初始化producer

}
func initConfig() {
	//sarama.Logger = log{}
	cfg := sarama.NewConfig()
	cfg.Version = sarama.V2_2_0_0
	cfg.Producer.Return.Errors = true
	cfg.Net.SASL.Enable = false
	cfg.Producer.Return.Successes = true //这个是关键，否则读取不到消息
	cfg.Producer.RequiredAcks = sarama.WaitForAll
	cfg.Producer.Partitioner = sarama.NewManualPartitioner //允许指定分组
	cfg.Consumer.Return.Errors = true
	cfg.Consumer.Offsets.Initial = sarama.OffsetOldest
	//cfg.Group.Return.Notifications = true
	cfg.ClientID = "service-exchange-api"
	KafkaClient.Config = KafkaConfig{
		Address: []string{fmt.Sprintf("%v:%v", configs.SERVER_IP, configs.KAFKA_PORT)},
		Configs: cfg,
	}
}

func initClient() {
	//创建链接 创建客户机
	client, err := sarama.NewClient(KafkaClient.Config.Address, KafkaClient.Config.Configs)
	if err != nil {
		KafkaClient.Client = client
	}
}

func initConsumer() {
	consumerGroup, err := sarama.NewConsumerGroupFromClient("default-group", KafkaClient.Client)
	//client, err := sarama.NewConsumerGroup([]string{"127.0.0.1:9092"}, "group-1", cfg.Config)
	if err != nil {
		fmt.Println(err)
	} else {
		KafkaClient.ConsumerGroup = consumerGroup
	}

}

func initBroker() {

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

func initProducer() {
	producer, err := sarama.NewSyncProducerFromClient(KafkaClient.Client)
	if err != nil {
		logger.Logger.Info(fmt.Sprintf("kafka's producer init fail <err : %v", err.Error()))
	} else {
		KafkaClient.Producer = producer
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
	logger.Logger.Info(fmt.Sprintf("ProduceMsg Start  topic name is:%v ---------------------", topicName))
	partition, offset, err := client.Producer.SendMessage(msg)
	if err != nil {
		logger.Logger.Info(fmt.Sprintf("ProduceMsg to topic [%v] err :%v", topicName, err.Error()))
		return err
	}
	logger.Logger.Info(fmt.Sprintf("ProduceMsg to topic:%v  success,   content :%v,   partition :%v  offset:%v ", topicName, msg, partition, offset))
	return nil
}

func (client *kafkaClient) SubscribeMsg(topicName string) {
	go func() {
		for err := range client.ConsumerGroup.Errors() {
			fmt.Println(err)
		}
	}()
	ctx, _ := context.WithCancel(context.Background())
	hand := MainHandler{}
	for {
		err := client.ConsumerGroup.Consume(ctx, []string{topicName}, &hand)
		if err != nil {
			fmt.Println(err)
			break
		}
		if ctx.Err() != nil {
			break
		}
	}
	/*for {
		msg := <-partitionConsumer.Messages()
		pom.MarkOffset(msg.Offset + 1, "备注")
		fmt.Printf("[%s] : Consumed message: [%s], offset: [%d]\n",item, string(msg.Value), msg.Offset)
	}*/

}

type MainHandler struct {
}

func (m *MainHandler) Setup(sess sarama.ConsumerGroupSession) error {
	// 如果极端情况下markOffset失败，需要手动同步offset
	return nil
}

func (m *MainHandler) Cleanup(sess sarama.ConsumerGroupSession) error {
	// 如果极端情况下markOffset失败，需要手动同步offset
	return nil
}

//此方法会自动控制偏移值，当分组里的主题消息被接收到时，则偏移值会进行加1 他是跟着主题走的
func (m *MainHandler) ConsumeClaim(sess sarama.ConsumerGroupSession, claim sarama.ConsumerGroupClaim) error {
	// NOTE:
	// Do not move the code below to a goroutine.
	// The `ConsumeClaim` itself is called within a goroutine, see:
	// https://github.com/Shopify/sarama/blob/master/consumer_group.go#L27-L29
	for message := range claim.Messages() {
		fmt.Println(fmt.Printf("Message claimed: value = %s, timestamp = %v, topic = %s Offset  = %s", string(message.Value), message.Timestamp, message.Topic, message.Offset))
		sess.MarkMessage(message, "")
	}
	return nil
}
