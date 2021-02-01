<?php
return [
    'SERVER_NAME' => "EasySwoole",
    'MAIN_SERVER' => [
        'LISTEN_ADDRESS' => '0.0.0.0',
        'PORT' => 9501,
        'SERVER_TYPE' => EASYSWOOLE_WEB_SERVER, //可选为 EASYSWOOLE_SERVER  EASYSWOOLE_WEB_SERVER EASYSWOOLE_WEB_SOCKET_SERVER
        'SOCK_TYPE' => SWOOLE_TCP,
        'RUN_MODEL' => SWOOLE_PROCESS,
        'SETTING' => [
            'worker_num' => 8,
            'reload_async' => true,
            'max_wait_time'=>3
        ],
        'TASK'=>[
            'workerNum'=>4,
            'maxRunningNum'=>128,
            'timeout'=>15
        ]
    ],
    'TEMP_DIR' => null,
    'LOG_DIR' => null,

    "DATABASE"=>[
        "MONGO"=>[
            // 数据库类型
            'type'           => '\think\mongo\Connection',
            // 设置查询类
            'query'			 => '\think\mongo\Query',
            // 服务器地址
            'hostname'       => '127.0.0.1',
            // 集合名
            'database'       => 'fileserve',
            // 用户名
            'username'       => 'admin',
            // 密码
            'password'       => 'password',
            // 端口
            'hostport'       => '',
        ]
    ],
];
