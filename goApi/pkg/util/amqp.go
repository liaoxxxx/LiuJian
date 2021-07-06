package util

import (
	"fmt"
	"github.com/streadway/amqp"
	"goApi/configs"
	"goApi/pkg/logger"
	"log"
)

type rabbitMqClient struct {
	conn    *amqp.Connection
	channel *amqp.Channel
}

var RabbitMQClient rabbitMqClient

func init() {
	//链接
	logger.Logger.Info(connectUrl(configs.AmqpUser, configs.AmqpPassword, configs.AmqpHost, configs.AmqpPort))
	conn, err := amqp.Dial(connectUrl(configs.AmqpUser, configs.AmqpPassword, configs.AmqpHost, configs.AmqpPort))
	if err != nil {
		failOnError(err, "Failed to connect to RabbitMQ")
		return
	}
	RabbitMQClient.conn = conn
	//通道
	ch, err := RabbitMQClient.conn.Channel()
	RabbitMQClient.channel = ch
	if err != nil {
		failOnError(err, "Failed to open a channel")
		return
	}

	//defer conn.Close()
}

func failOnError(err error, msg string) {
	if err != nil {
		logger.Logger.Fatal(fmt.Sprintf("%s: %s", msg, err))
	}
}

func connectUrl(user, password, host, port string) string {
	return fmt.Sprintf("amqp://%s:%s@%v:%s/admin", user, password, host, port)
}

func (rClient *rabbitMqClient) Publish(topicName string, messageBody []byte) (err error) {
	queueOne, err := rClient.channel.QueueDeclare(
		topicName, // name
		false,     // durable
		false,     // delete when unused
		false,     // exclusive
		false,     // no-wait
		nil,       // arguments
	)
	if err != nil {
		failOnError(err, "Failed to declare a queue")
		return err
	}

	err = rClient.channel.Publish(
		"",            // exchange
		queueOne.Name, // routing key
		false,         // mandatory
		false,         // immediate
		amqp.Publishing{
			ContentType: "text/plain",
			Body:        messageBody,
		})
	if err != nil {
		failOnError(err, "Failed to publish a message")
		log.Printf(" [x] Sent %s", messageBody)
		return err
	}
	return nil

}

func (rClient *rabbitMqClient) Received(topicName string, handle func([]byte) error) error {
	q, err := rClient.channel.QueueDeclare(
		topicName, // name
		false,     // durable
		false,     // delete when unused
		false,     // exclusive
		false,     // no-wait
		nil,       // arguments
	)
	if err != nil {
		failOnError(err, "Failed to declare a queue")
		return err
	}

	msgList, err := rClient.channel.Consume(
		q.Name, // queue
		"",     // consumer
		true,   // auto-ack
		false,  // exclusive
		false,  // no-local
		false,  // no-wait
		nil,    // args
	)
	if err != nil {
		failOnError(err, "Failed to register a consumer")
		return err
	}

	forever := make(chan bool)

	go func() {
		for msg := range msgList {
			log.Printf("Received a message: %s", msg.Body)
			err = handle(msg.Body)
			if err != nil {
				logger.Logger.Warn("OrderRecycle Create err: " + err.Error())
				continue
			}
		}
	}()

	log.Printf(" [*] Waiting for messages. To exit press CTRL+C")
	<-forever
	return nil
}
