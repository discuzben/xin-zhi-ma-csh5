<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2018/11/6
 * Time: 11:50
 */

namespace app\common\util;

use app\common\lib\File;

class Exception
{
    // 调试阶段
    public static function debug($excep){
        $file   = $excep->getFile();
        $line   = $excep->getLine();
        $msg    = $excep->getMessage();
        $trace  = $excep->getTrace();
        $current = $trace[0];
        $log = [
            // 目标错误
            'target' => [
                'file' => $file ,
                'line' => $line ,
                'message' => $msg ,
                'operation' => isset($current['class']) ? sprintf("%s::%s" , $current['class'] , $current['function']) : $current['function']
            ] ,
            // 回溯跟踪
            'trace' => []
        ];
        $others = array_slice($trace , 1);
        foreach ($others as $v)
        {
            $log['trace'][] = self::line($v['file'] ?? '' , $v['line'] ?? '' , $v['function'] ?? '' , $v['class'] ?? '');
        }
        // 设置该头部是便于调试工具显示数据
        header('content-type: application/json');
        // 输出错误信息
        exit(json_encode([
            'code' => '0002' ,
            'msg'  => '' ,
            'data' => $log
        ]));
    }

    // 生产阶段
    public static function product($excep){
        // 记录错误日志
        $file   = $excep->getFile();
        $line   = $excep->getLine();
        $message    = $excep->getMessage();
        $trace  = $excep->getTrace();
        $current = $trace[0];
        $operation = isset($current['class']) ? sprintf("%s::%s" , $current['class'] , $current['function']) : $current['function'];
        $others = array_slice($trace , 1);

        $msg = str_repeat('-' , 10);
        $msg .= 'Exception start';
        $msg .= str_repeat('-' , 10);
        $msg .= PHP_EOL;
        $msg .= sprintf("Time: %s\n" , date('Y-m-d H:i:s'));
        $msg .= sprintf("Target： message: %s；operation：%s；file：%s；line：%s；\n" , $message , $operation , $file , $line);
        $msg .= "Trace ：\n";
        foreach ($others as $v)
        {
            $class      = $v['class'] ?? '';
            $function   = $v['function'] ?? '';
            $operation  = empty($class) ? sprintf("%s" , $function) : sprintf("%s::%s" , $class , $function);
            $msg .= sprintf("operation：%s；file：%s；line：%s；\n" , $operation , $v['file'] ?? '' , $v['line'] ?? '');
        }
        $msg .= str_repeat('-' , 10);
        $msg .= 'Exception end  ';
        $msg .= str_repeat('-' , 10);
        $msg .= PHP_EOL;
        $msg .= PHP_EOL;
        // 写入到日志
        $log_dir    = config('log.log_dir');
        $log        = sprintf('%sexception.log' , $log_dir);
        File::write($log , $msg , 'a');
        // todo 如果需要，可以发送邮件通知开发者 ...

        // 再次抛出异常，让 thinkphp 系统自身去捕获错误
        // 注意了，这个地方状态码是 1000，由于错误
        exit(c_response('1001' , '系统发生故障，请联系客服反馈！谢谢~'));

        exit(json_encode([
            'code' => '0002' ,
            'msg'  => '系统发生故障，请联系客服反馈！谢谢~' ,
            'data' => null
        ]));
    }

    // 生成单条信息
    public static function line($file , $line , $function , $class = ''){
        return [
            'file' => $file ,
            'line' => $line ,
            'operation' => empty($class) ? sprintf("%s" , $function) : sprintf("%s::%s" , $class , $function)
        ];
    }
}