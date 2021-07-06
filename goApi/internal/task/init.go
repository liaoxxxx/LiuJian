package task

import "github.com/streadway/amqp"

type Consumer interface {
	handle(delivery amqp.Delivery) error
}
