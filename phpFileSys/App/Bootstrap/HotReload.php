<?php


namespace App\Bootstrap;
use EasySwoole\EasySwoole\ServerManager;

class HotReload
{
    public static function boot(){

        //热更新 配置同上别忘了添加要检视的目录
        $hotReloadOptions = new \EasySwoole\HotReload\HotReloadOptions;
        $hotReload = new \EasySwoole\HotReload\HotReload($hotReloadOptions);
        $hotReloadOptions->setMonitorFolder([EASYSWOOLE_ROOT . '/App',]);
        $server = ServerManager::getInstance()->getSwooleServer();
        $hotReload->attachToServer($server);

    }
}
