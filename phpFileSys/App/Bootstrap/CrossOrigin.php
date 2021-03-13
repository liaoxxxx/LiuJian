<?php


namespace App\Bootstrap;;
use EasySwoole\Component\Di;

class CrossOrigin
{
    public static function boot(){
        //配置跨域
        // onRequest v3.4.x+
        Di::getInstance()->set(\EasySwoole\EasySwoole\SysConst::HTTP_GLOBAL_ON_REQUEST, function (\EasySwoole\Http\Request $request, \EasySwoole\Http\Response $response) {
            $response->withHeader("Access-Control-Allow-Origin", "*");
            $response->withHeader("Access-Control-Allow-Methods", 'GET, POST, DELETE, PUT, PATCH, OPTIONS');
            $response->withHeader("Access-Control-Allow-Credentials", "true");
            $response->withHeader("Access-Control-Allow-Headers", "Authorization, User-Agent, Keep-Alive, Content-Type, X-Requested-With, Authori-zation, Authorization, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-Requested-With, AccessToken, X-CSRF-Token, Token, token");
            $response->withHeader("Access-Control-Max-Age", "1728000");
            $response->withHeader("Content-Type", "application/json; charset=utf-8");
            if ($request->getMethod() === 'OPTIONS') {
                file_put_contents(EASYSWOOLE_ROOT.'/Temp/liaolog',print_r($request->getMethod(),true),FILE_APPEND);
                $response->withStatus(http_response_code());
                $response->end();
                return  false;   //务必返回 false，断掉 中间件的后续操作的意思？
            }
            return true;
        });
    }
}
