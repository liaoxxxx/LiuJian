<?php


namespace App\HttpController;


use App\Enum\Server;
use App\Model\MongoDb\Driver;
use EasySwoole\Http\AbstractInterface\Controller;
use EasySwoole\Http\Message\UploadFile;
use App\Common\Util\Response;
use EasySwoole\Utility\Random;

class Upload extends Controller
{

    /**
     * @return false
     */
    public function single()
    {




        /** @var UploadFile $file */
        $file = $this->request()->getUploadedFile('file');
        if(!isset($file)|| empty($file)){
            $this->response()->write(json_encode(['msg'=>"file not exist"]));
            return false;
        }
        $user="u-internal";
        $month=date("Ym");
        $day=date("d");
        $fileExtType=$file->getClientMediaType();
        $fileExtType=".".substr($fileExtType,strpos($fileExtType,'/')+1,strlen($fileExtType)-1);  // : .jpeg


        //   :  /upload/u-username/202102/01/abcdhbfdfg16525656652.jpeg;
        $nameSuffix=   '/upload/'."$user/$month/$day/".Random::character(10).time() .$fileExtType;
        $targetName= '/public'.$nameSuffix;
        $file->moveTo(EASYSWOOLE_ROOT . $targetName);
        $data=[
            'domain'=>Server::DOMAIN,
            'suffixPath'=>$nameSuffix,
            'fullPath'=> Server::DOMAIN.$nameSuffix,

        ];
        file_put_contents(EASYSWOOLE_ROOT.'/Temp/liao.log',print_r(json_encode($data).PHP_EOL,true),FILE_APPEND);
        $this->response()->write(json_encode($data));
        return  true;
        ///** @var UploadFile $file */
        //$file = $request->getUploadedFile('file');//获取一个上传文件,返回的是一个\EasySwoole\Http\Message\UploadFile的对象

    }


    public function multi(){
        $request=  $this->request();
        $files = $request->getUploadedFiles();
        $fileOriginName=$files->getTempName();
    }
}