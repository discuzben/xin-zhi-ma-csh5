<?php

/**
 * 短信验证码发送工具类库
 */

namespace app\common\util;

use Exception;
use app\common\util\Http;


class Sms {

    // 发送登录验证码
    public static function login($mobile = '' , $code = '' ){
        $template = config('sms.template');
        $login = $template['login'];
        self::sendCode($mobile , $code , $login['id'] , $login['value']);
    }

    // 发送注册验证码
    public static function register($mobile = '' , $code = '' ){
        $template = config('sms.template');
        $login = $template['register'];
        self::sendCode($mobile , $code , $login['id'] , $login['value']);
    }

    // 发送忘记密码验证码
    public static function password($mobile = '' , $code = '' ){
        $template = config('sms.template');
        $login = $template['password'];
        self::sendCode($mobile , $code , $login['id'] , $login['value']);
    }

    // 发送验证码
    public static function sendCode($mobile , $code , $tpl_id , $tpl_value){
        $config = [
            'key'       => config('sms.key') ,
            'mobile'    => $mobile ,
            'tpl_id'    => $tpl_id ,
            'tpl_value' => urlencode(sprintf($tpl_value , $code))
        ];
        $api = config('sms.api');
        //请求发送短信
        $res = Http::post($api , $config);
        if(!$res){
            //返回内容异常，以下可根据业务逻辑自行修改
            throw new Exception('请求发送短信失败');
        }
        $result     = json_decode($res,true);
        $error_code = $result['error_code'];
        if($error_code == 0){
            //状态为0，说明短信发送成功
            // throw new Exception("短信发送成功,短信ID：".$result['result']['sid']);
            return ;
        }
        //状态非0，说明失败
        throw new Exception("短信发送失败({$error_code})：{$result['reason']}");
    }

}
