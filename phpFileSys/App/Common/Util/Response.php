<?php

namespace App\Common\Util;

class Response
{

   private int $code =200;

   private int $msg;

    /**
     * @var mixed
     */
   private  $data=[];

   private string $status='';

   private int $errCode=0;

    /**
     * @param string $msg
     * @param $data
     * @return string
     */
    public function success(string $msg,$data ): string{
        $this->code = http_response_code();

        $this->msg = $msg ?? 'success';

        $this->data = $data ?? [];

        $this->status = 'success';

        $this->errCode =0;

        return $this->toString();
    }


    /**
     * @param string $msg
     * @param int $errCode
     * @param $data
     * @return string
     */
    public function fail(string $msg, int $errCode, $data): string
    {
        $this->code = 400;

        $this->msg = $msg ?? 'error';

        $this->data = $data ?? [];

        $this->status = 'error';

        $this->errCode = $errCode ?? 500;

        return $this->toString();
    }

    public function toString(){
        return json_encode([
            'code' => $this->code,

            'msg' => $this->msg,

            'data' => $this->data,

            'status' => $this->status,

            'errCode' => $this->errCode,
        ]);
    }
}