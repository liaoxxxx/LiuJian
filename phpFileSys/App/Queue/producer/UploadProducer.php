<?php


namespace App\Queue\Producer;

use App\Enum\QueueConfig;
use EasySwoole\Component\Process\AbstractProcess;
use EasySwoole\Kafka\Config\ProducerConfig;
use EasySwoole\Kafka\Kafka;


class UploadProducer extends AbstractProcess
{
    protected function run($arg)
    {
        go(function () {
            $config = new ProducerConfig();
            $config->setMetadataBrokerList('127.0.0.1:9092');
            $config->setBrokerVersion('0.9.0');
            $config->setRequiredAck(1);

            $kafka = new kafka($config);

            $result = $kafka->producer()->send([
                [
                    'topic' => QueueConfig::UPLOAD_TOPIC_NAME,
                    'value' => 'message--',
                    'key' => 'key--',
                    'time'=>date("Y-m-d H:i:s")
                ],
            ]);
        });
    }
}
