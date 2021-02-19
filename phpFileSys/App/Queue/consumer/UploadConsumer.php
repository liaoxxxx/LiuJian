<?php


namespace App\Queue\Consumer;

use App\Enum\QueueConfig;
use EasySwoole\Component\Process\AbstractProcess;
use EasySwoole\Kafka\Config\ConsumerConfig;
use EasySwoole\Kafka\Kafka;

class UploadConsumer extends AbstractProcess
{
    protected function run($arg)
    {
        go(function () {
            $config = new ConsumerConfig();
            $config->setRefreshIntervalMs(1000);
            $config->setMetadataBrokerList('127.0.0.1:9092');
            $config->setBrokerVersion('0.9.0');
            $config->setGroupId('test');

            $config->setTopics([ QueueConfig::UPLOAD_TOPIC_NAME ]);
            $config->setOffsetReset('earliest');

            $kafka = new Kafka($config);
            // 设置消费回调
            $func = function ($topic, $partition, $message) {
                file_put_contents(EASYSWOOLE_ROOT . '/Temp/liao.log',
                    print_r(json_encode([
                            'topic' => $topic,
                            'partition' => $partition,
                            'message' => $message,
                        ]) . PHP_EOL, true),
                    FILE_APPEND);
            };
            $kafka->consumer()->subscribe($func);
        });
    }
}