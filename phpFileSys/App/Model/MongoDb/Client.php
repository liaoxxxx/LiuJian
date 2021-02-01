<?php

namespace App\Model\MongoDb;

use EasySwoole\Component\Singleton;
use EasySwoole\SyncInvoker\SyncInvoker;

class Client extends SyncInvoker
{
    use Singleton;
}