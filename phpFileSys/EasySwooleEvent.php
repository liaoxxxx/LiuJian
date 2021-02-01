<?php


namespace EasySwoole\EasySwoole;


use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\EasySwoole\Swoole\EventRegister;

class EasySwooleEvent implements Event
{
    public static function initialize()
    {
        date_default_timezone_set('Asia/Shanghai');
    }

    public static function mainServerCreate(EventRegister $register)
    {
        // 配置同上别忘了添加要检视的目录
        $hotReloadOptions = new \EasySwoole\HotReload\HotReloadOptions;
        $hotReload = new \EasySwoole\HotReload\HotReload($hotReloadOptions);
        $hotReloadOptions->setMonitorFolder([EASYSWOOLE_ROOT . '/App',]);

        $server = ServerManager::getInstance()->getSwooleServer();
        $hotReload->attachToServer($server);

        //配置跨域
        // onRequest v3.4.x+
        \EasySwoole\Component\Di::getInstance()->set(\EasySwoole\EasySwoole\SysConst::HTTP_GLOBAL_ON_REQUEST,function (\EasySwoole\Http\Request $request, \EasySwoole\Http\Response $response){
            $response->withHeader('Access-Control-Allow-Origin', '*');
            $response->withHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
            $response->withHeader('Access-Control-Allow-Credentials', 'true');
            $response->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
            if ($request->getMethod() === 'OPTIONS') {
                $response->withStatus(200);
                $response->end();
            }
            return true;
        });



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