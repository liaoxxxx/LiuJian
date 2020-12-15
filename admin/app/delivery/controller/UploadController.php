<?php


namespace app\delivery\controller;


use app\admin\model\system\SystemAttachment as SystemAttachmentModel;
use common\basic\BaseController;
use common\services\UploadService as Upload;

/**
 * 微信公众号
 * Class TestController
 * @package app\api\controller\wechat
 */
class UploadController extends BaseController
{


    /**
     * 测试
     * @return void
     */
    public function index()
    {
        $pid = input('pid')!= NULL ?input('pid'):session('pid');
        $upload_type = $this->request->get('upload_type',2);
        try{
            $path = make_path('attach',2,true);

            $res = Upload::instance()->setUploadPath('wechat/image/' . date('Ymd'))->image($_POST['file']);
            if(is_object($res) && $res->status === false){
                $info = array(
                    'code' =>400,
                    'msg'  =>'上传失败：'.$res->error,
                    'src'  =>''
                );
            }else if(is_string($res)){
                $info = array(
                    'code' =>400,
                    'msg'  =>'上传失败：'.$res,
                    'src'  =>''
                );
            }else if(is_array($res)){
                $res['dir'] = str_replace('\\','/',$res['dir']);
                $res['thumb_path'] = str_replace('\\','/',$res['thumb_path']);
                SystemAttachmentModel::attachmentAdd($res['name'],$res['size'],$res['type'],$res['dir'],$res['thumb_path'],$pid,$res['image_type'],$res['time']);
                $info = array(
                    'code' =>200,
                    'msg'  =>'上传成功',
                    'src'  =>$res['dir']
                );
            }
        }catch (\Exception $e){
            $info = [
                'code' =>400,
                'msg'  =>'上传失败：'.$e->getMessage(),
                'src'  =>''
            ];
        }
        echo json_encode($info);


    }



}