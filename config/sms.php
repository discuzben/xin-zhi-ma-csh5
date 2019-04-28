<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2018/12/3
 * Time: 17:44
 *
 * 发送短信配置文件
 */

return [
    // open id
    'key' => '95e690e08e1699f082ba2ff7b74f7c62' ,
    // 短信接口
    'api' => 'http://v.juhe.cn/sms/send' ,
    // 模板 id
    'template' => [
        // 登录验证码
        'login' => [
            'id' => '111831' ,
            // 模板值
            'value' => '#code#=%s'
        ] ,
        // 注册验证码
        'register' => [
            'id' => '119622' ,
            // 模板值
            'value' => '#code#=%s'
        ] ,
        // 忘记密码验证码
        'password' => [
            'id' => '119623' ,
            // 模板值
            'value' => '#code#=%s'
        ] ,
    ]
];
