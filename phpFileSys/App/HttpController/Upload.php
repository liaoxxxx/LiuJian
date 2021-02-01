<?php


namespace App\HttpController;


use App\Model\MongoDb\Driver;
use EasySwoole\Http\AbstractInterface\Controller;
use EasySwoole\Http\Message\UploadFile;
use \App\model\mongodb\Client  as MongoClient ;
use EasySwoole\Utility\Random;

class Upload extends Controller
{

    public function single()
    {




        /** @var UploadFile $file */
        $file = $this->request()->getUploadedFile('file');

        $user="u-internal";
        $month=date("Ym");
        $day=date("d");
        $fileExtType=$file->getClientMediaType();
        var_dump($fileExtType);
        file_put_contents(EASYSWOOLE_ROOT.'/Temp/liaolog',print_r($fileExtType.PHP_EOL,true),FILE_APPEND);
        $this->response()->write(json_encode($fileExtType));
        $nameSuffix= Random::number();
        $targetName= '/public/upload/'."/$user/$month/$day/$nameSuffix";
        $file->moveTo(EASYSWOOLE_ROOT . $targetName);
        $this->response()->write(7777);

        ///** @var UploadFile $file */
        //$file = $request->getUploadedFile('file');//获取一个上传文件,返回的是一个\EasySwoole\Http\Message\UploadFile的对象

    }


    public function multi(){
        $request=  $this->request();
        $files = $request->getUploadedFiles();
        $fileOriginName=$files->getTempName();
    }
}