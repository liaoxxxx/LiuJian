<?php

namespace app;

use common\services\SystemConfigService;
use common\services\GroupDataService;
use common\utils\Json;
use common\utils\Redis;
use think\facade\Db;
use think\Service;

class AppService extends Service
{

    public $bind = [
        'json' => Json::class,
        'sysConfig' => SystemConfigService::class,
        'sysGroupData' => GroupDataService::class,
        'redis' => Redis::class,
    ];

    public function boot()
    {

    }
}
