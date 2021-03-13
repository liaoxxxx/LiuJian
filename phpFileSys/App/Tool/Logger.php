<?php


namespace App\Tool;


use Exception;

class Logger
{
    /**
     * 在文件中写入调试日志
     * @param $msg
     * @param $file
     * @param $line
     * @param string $logPath
     */
    static function fileLog($msg, $file, $line, $logPath = '')
    {
        $logPath = !empty($logPath) ? runtime_path() . $logPath : runtime_path() . 'cli.log';
        if (!is_string($msg)) {
            $msg = json_encode($msg);
        }
        $msg = date('Y-m-d H:i:s') . '  文件:' . $file . "  行: " . $line . ' msg:      ' . $msg;
        try {
            file_put_contents($logPath, print_r($msg . PHP_EOL, true), FILE_APPEND);
        } catch (Exception $exception) {

        }

    }
}
