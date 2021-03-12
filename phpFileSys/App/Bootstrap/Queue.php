<?php


namespace App\Bootstrap;
use App\Queue\Producer\UploadProducer as UploadProducerProcess;
use App\Queue\Consumer\UploadConsumer as UploadConsumerProcess;
use EasySwoole\EasySwoole\ServerManager;

class Queue
{
    public static function boot(){
        //kafka
        // TODO: Implement mainServerCreate() method.
        // 生产者
        ServerManager::getInstance()->getSwooleServer()->addProcess((new UploadProducerProcess())->getProcess());
        // 消费者
        ServerManager::getInstance()->getSwooleServer()->addProcess((new UploadConsumerProcess())->getProcess());
    }
}
