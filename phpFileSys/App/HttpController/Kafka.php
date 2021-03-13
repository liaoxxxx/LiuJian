<?php


namespace App\HttpController;


use App\Enum\Server;
use App\Model\MongoDb\Driver;
use EasySwoole\Http\AbstractInterface\Controller;
use EasySwoole\Http\Message\UploadFile;
use App\Common\Util\Response;
use EasySwoole\Utility\Random;

class Kafka extends Controller
{

    /**
     * @return false
     */
    public function publish()
    {

    }

}
