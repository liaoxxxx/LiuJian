<?php


namespace App\Bootstrap;

use EasySwoole\EasySwoole\ServerManager;

class MongoDb
{
    public static function boot(){
        // 配置 Invoker
        $invokerConfig = \App\Model\MongoDb\Client::getInstance()->getConfig();
        $invokerConfig->setDriver(new \App\Model\MongoDb\Driver()); // 配置 MongoDB 客户端协程调用驱动
        // 以下这些配置都是可选的，可以使用组件默认的配置
        /*
        $invokerConfig->setMaxPackageSize(2 * 1024 * 1024); // 设置最大允许发送数据大小，默认为 2M【注意：当使用 MongoDB 客户端查询大于 2M 的数据时，可以修改此参数】
        $invokerConfig->setTimeout(3.0); // 设置 MongoDB 客户端操作超时时间，默认为 3.0 秒;
        */
        // 注册 Invoker
        \App\Model\MongoDb\Client::getInstance()->attachServer(ServerManager::getInstance()->getSwooleServer());
    }
}
