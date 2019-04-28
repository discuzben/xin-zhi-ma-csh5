<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2018/11/1
 * Time: 10:26
 */
return [
    // 短时间: 30min
    'short_duration' => 30 * 60  ,
    // 普通时常: 7d
    'duration' => 15 * 24 * 3600 ,
    // 长时间: 30d
    'long_duration' => 30 * 24 * 3600 ,
    // 验证码时效 10min
    'code_duration' => 10 * 60 ,
    // 验证码的发送频率: 1/60
    'code_interval' => 60
];