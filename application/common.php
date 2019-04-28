<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

use think\Db;

/**
 * CURL请求
 * @param $url 请求url地址
 * @param $method 请求方法 get post
 * @param null $postfields post数据数组
 * @param array $headers 请求header信息
 * @param bool|false $debug  调试开启 默认false
 * @return mixed
 */
function httpRequest($url, $method, $postfields = null, $headers = array(), $debug = false) {
    $method = strtoupper($method);
    $ci = curl_init();
    /* Curl settings */
    curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
    curl_setopt($ci, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.2; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0");
    curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 60); /* 在发起连接前等待的时间，如果设置为0，则无限等待 */
    curl_setopt($ci, CURLOPT_TIMEOUT, 7); /* 设置cURL允许执行的最长秒数 */
    curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
    switch ($method) {
        case "POST":
            curl_setopt($ci, CURLOPT_POST, true);
            if (!empty($postfields)) {
                $tmpdatastr = is_array($postfields) ? http_build_query($postfields) : $postfields;
                curl_setopt($ci, CURLOPT_POSTFIELDS, $tmpdatastr);
            }
            break;
        default:
            curl_setopt($ci, CURLOPT_CUSTOMREQUEST, $method); /* //设置请求方式 */
            break;
    }
    $ssl = preg_match('/^https:\/\//i',$url) ? TRUE : FALSE;
    curl_setopt($ci, CURLOPT_URL, $url);
    if($ssl){
        curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
        curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, FALSE); // 不从证书中检查SSL加密算法是否存在
    }
    //curl_setopt($ci, CURLOPT_HEADER, true); /*启用时会将头文件的信息作为数据流输出*/
    curl_setopt($ci, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ci, CURLOPT_MAXREDIRS, 2);/*指定最多的HTTP重定向的数量，这个选项是和CURLOPT_FOLLOWLOCATION一起使用的*/
    curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ci, CURLINFO_HEADER_OUT, true);
    /*curl_setopt($ci, CURLOPT_COOKIE, $Cookiestr); * *COOKIE带过去** */
    $response = curl_exec($ci);
    $requestinfo = curl_getinfo($ci);
    $http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
    if ($debug) {
        echo "=====post data======\r\n";
        var_dump($postfields);
        echo "=====info===== \r\n";
        print_r($requestinfo);
        echo "=====response=====\r\n";
        print_r($response);
    }
    curl_close($ci);
    return $response;
    //return array($http_code, $response,$requestinfo);
}


/**
 * 获取用户信息
 * @param $user_id_or_name  用户id 邮箱 手机 第三方id
 * @param int $type  类型 0 user_id查找 1 邮箱查找 2 手机查找 3 第三方唯一标识查找
 * @param string $oauth  第三方来源
 * @return User model
 */
function get_user_info($user_id_or_name,$type = 0,$oauth=''){
    $map = array();
    if($type == 0)
        $map['uid'] = $user_id_or_name;
    if($type == 1)
        $map['email'] = $user_id_or_name;
    if($type == 2)
        $map['phone'] = $user_id_or_name;
    if($type == 3){
        $map['wx_openid'] = $user_id_or_name;
        $map['oauth'] = $oauth;
    }
    $user =\app\mobile\model\User::user($map);
    return $user;
}


// 字符串长度验证
function check_len($str , $len , $sign = 'eq'){
    $range = ['gt' , 'gte' , 'lt' , 'lte' , 'eq'];
    $sign = in_array($sign , $range) ? $sign : 'eq';
    $str_len = mb_strlen($str);
    switch ($sign)
    {
        case 'gt':
            return $str_len > $len;
        case 'gte':
            return $str_len >= $len;
        case 'lt':
            return $str_len < $len;
        case 'lte':
            return $str_len <= $len;
        case 'eq':
            return $str_len = $len;
        default:
            throw new Exception('不支持的比较符类型');
    }
}
// 检查手机号码
function check_phone($phone){
    return (bool) (preg_match('/^[1][3-8]\d{9}$/u' , $phone) || preg_match('/^\d+\-\d+(\-[0-9\-]+)?$/' , $phone));
}
// 检查价格
function check_price($price){
    return (bool) preg_match('/^[1-9]?\d*(\.\d{0,2})?$/' , $price);
}
// 检查年份
function check_year($year){
    $reg = '/^\d{4}$/';
    return (bool) preg_match($reg , $year);
}
// 检查日期格式
function check_date($date){
    $reg = '/^\d{4}\-(0[1-9]|1[0-2])\-(0[1-9]|[1-2]\d|3[0-1])$/';
    return (bool) preg_match($reg , $date);
}
// 检查数字
function check_num($num , $len = 0){
    if ($len === 0) {
        return (bool) preg_match("/^\d+$/" , $num);
    }
    $reg = "/^\d+(\.\d{0,{$len}})?$/";
    return (bool) preg_match($reg , $num);
}
// 检查密码
function check_password($password){
    $reg = "/^.{6,}$/";
    return (bool) preg_match($reg , $password);
}
// 检查电子邮箱
function check_email($mail){
    $reg = "/^\.+@\.+$/";
    return (bool) preg_match($reg , $mail);
}

// 系统根目录
function base_path(){
    return format_path(str_replace('\\' , '/' , realpath(__DIR__ . '/../'))) . '/';
}

// 资源目录
function public_path(){
    return base_path() . 'public/';
}

// 生成网络路径
function gen_url($path , $is_relative = true){
    $path = format_path(str_replace('\\' , '/' , realpath($path)));
    $host = $is_relative ? '' : config('app.host');
    $host = empty($host) ? '/' : rtrim($host , '/') . '/';
    return empty($path) ? '' : $host . str_replace(public_path() , '' , $path);
}

/**
 *  获取正确的配置文件
 * @param key 配置文件中定义的键名
 * @param val 待获取键值的键名
 *
 * @return 配置文件中指定范围搜索到的 key 的键值
 *
 * 比如：config/app.php 中定义了:
 * 'user_level' => [1 => '经纪人' , 2 => '服务商']
 * 我要获取 user_level.1 的值，怎么获取？
 *
 * get_correct_value('app.user_level' , 1)
 */
function get_correct_value($key , $val){
    $range = config($key);
    foreach ($range as $k => $v)
    {
        if ($k == $val) {
            return $v;
        }
    }
    return '';
}


// 保留多少位小数点
function fix_number($num , $len = 0){
    $str = number_format($num , $len);
    $str = preg_replace('/[^0-9\.]/' , '' , $str);
    return floatval($str);
}

// 获取数字
function number($str = '' , $len = 0){
    $str = preg_replace('/[^0-9\.]*/' , '' , $str);
    return fix_number($str , $len);
}

/*
 * 简单的随机数生成函数
 * 按要求返回随机数
 * @param  Integer    $len        随机码长度
 * @param  String     $type       随机码类型  letter | number | mixed
 * @return Array
 */
function random(int $len = 4 , string $type = 'mixed' , bool $is_return_str = true){
    $type_range = array('letter','number','mixed');
    if (!in_array($type , $type_range)){
        throw new Exception('参数 2 类型错误');
    }
    if (!is_int($len) || $len < 1) {
        $len = 1;
    }
    $result = [];
    $letter = array('a' , 'b' , 'c' , 'd' , 'e' , 'f' , 'g' , 'h' , 'i' , 'j' , 'k' , 'l' , 'm' , 'n' , 'o' , 'p' , 'q' , 'r' , 's' , 't' , 'u' , 'v' , 'w' , 'x' , 'y' , 'z');
    for ($i = 0; $i < count($letter) - $i; ++$i)
    {
        $letter[] = strtoupper($letter[$i]);
    }
    if ($type === 'letter'){
        for ($i = 0; $i < $len; ++$i)
        {
            $rand = mt_rand(0 , count($letter) - 1);
            shuffle($letter);

            $result[] = $letter[$rand];
        }
    }

    if ($type === 'number') {
        for ($i = 0; $i < $len; ++$i)
        {
            $result[] = mt_rand(0 , 9);
        }
    }
    if ($type === 'mixed'){
        for ($i = 0; $i < $len; ++$i)
        {
            $mixed = [];
            $rand  = mt_rand(0 , count($letter) - 1);
            shuffle($letter);
            $mixed[] = $letter[$rand];
            $mixed[] = mt_rand(0,9);
            $rand = mt_rand(0 , count($mixed) - 1);
            shuffle($mixed);
            $result[] = $mixed[$rand];
        }
    }
    return $is_return_str ? join('' , $result) : $result;
}
/*
 * 判断是否是无效值
 * @param  Mixed  $val
 * @return Boolean
 */
function is_valid($val){
    // 未定义变量
    if (!isset($val)) {
        return false;
    }

    // null
    if (is_null($val)) {
        return false;
    }

    // boolean false
    if ($val === false) {
        return false;
    }

    // 空值
    if ($val === '') {
        return false;
    }

    return true;
}
/*
 * 数组过滤：null 或 空字符串单元
 * @param   Array     $arr
 * @param   Boolean   $is_recursive  是否递归过滤
 * @return  Array    过滤后的数组
 */
function filter_arr(Array $arr = [] , $is_recursive = false){
    if (empty($arr)) {
        return $arr;
    }
    $is_recursive = is_bool($is_recursive) ? $is_recursive : false;
    $filter = function(Array $arr = [] , Array &$rel = []) use (&$filter , $is_recursive) {
        if (empty($arr)) {
            return true;
        }
        if (!$is_recursive) {
            foreach ($arr as $k => $v)
            {
                if (is_valid($v)) {
                    $rel[$k] = $v;
                }
            }
        } else {
            foreach ($arr as $k => $v)
            {
                if (is_array($v) && empty($v)) {
                    continue;
                }

                if (is_array($v) && !empty($v)) {
                    $rel[$k] = [];
                    $filter($v , $rel[$k]);
                } else {
                    if (is_valid($v)) {
                        $rel[$k] = $v;
                    }
                }
            }
        }
    };

    $rel = [];
    $filter($arr , $rel);
    return $rel;
}
/*
 * 编码转换 gb2312 -> utf-8
 * @param String $string
 * @return String
 */
function utf8($string = ''){
    return mb_convert_encoding($string , 'utf-8' , 'gb2312');
}
/*
 * 编码转换 utf-8 -> gb2312
 * @param String $string
 * @return String
 */
function gbk($string = ''){
    return mb_convert_encoding($string , 'gb2312' , 'utf-8');
}
// 导入数组单元到全局变量 ，并检查是否已存在，若存在则报错
function extract_global(Array $var_list = []){
    if (empty($var_list)) {
        return true;
    }
    foreach ($var_list as $k => $v)
    {
        if (isset($GLOBALS[$k])) {
            throw new Exception('已存在全局变量： ' . $k);
        }
        $GLOBALS[$k] = $v;
    }
}
/*
 * 给函数绑定参数
 * @param   Callable $func 待绑定参数的函数
 * @return  Closure
 */
function func_bind_args(Callable $func = null){
    $args = func_get_args();
    array_shift($args);
    return function() use($func , $args){
        return call_user_func_array($func , $args);
    };
}
/*
 * 获取当前使用平台：Pc / Mobile
 */
function get_platform(){
    $user_agent   = $_SERVER['HTTP_USER_AGENT'];
    $platform_reg = "/mobile/i";

    if (preg_match($platform_reg , $user_agent , $result) === 1) {
        return 'Mobile';
    }
    return 'Pc';
}
// 获取web服务器名称
function get_web_server(){
    $s = $_SERVER['SERVER_SOFTWARE'];
    $s_idx = 0;
    $e_idx = mb_strrpos($s , ' ');
    $server = mb_substring($s , $s_idx , $e_idx);
    return empty($server) ? $_SERVER['SERVER_SOFTWARE'] : $server;
}
/*
 * 判断一个数是偶数还是奇数
 */
function odd_even($num = 0){
    if (!is_numeric($num)) {
        throw new \Exception('参数 1 类型错误');
    }
    $b = 2;
    if ($num % $b !== 0) {
        return 'odd';
    }
    return 'even';
}
/**
 * 根据不同的服务器环境获取请求头
 * 目前支持的服务器有：Apache/Nginx
 * @param String $key 请求头
 * @return Boolean|String 失败时返回 false
 */
function get_request_header($key = ''){
    if (empty($key)) {
        return false;
    }
    if (function_exists('getallheaders')) {
        // Apache 服务器
        $headers = getallheaders();
        foreach ($headers as $k => $v)
        {
            if ($k === $key) {
                return $v;
            }
        }
    } else {
        // nginx 服务器
        $key = str_replace('-' , '_' , $key);
        $key = 'HTTP_' . $key;
        // Nginx 服务器
        foreach ($_SERVER as $k => $v)
        {
            if ($k === $key) {
                return $v;
            }
        }
    }
    return false;
}
// 复杂的随机数生成函数
// 需要 php 支持
function ssl_random(int $len = 256){
    return preg_replace('/[^A-z0-9]*/' , '' , base64_encode(openssl_random_pseudo_bytes($len)));
}


// 去除文件路径中最末尾的 /
function remove_backslash($str=''){
    $last_char = $str[mb_strlen($str) - 1];
    return $last_char === '/' || $last_char === "\\" ? mb_substr($str , 0 , mb_strlen($str) - 1) : $str;
}
// 处理路径中的斜杠
function chg_slash($path=''){
    return preg_replace('/\\\\/' , '/' , $path);
}
/*
 * 路径字符串处理
 * @return
	Linux    /usr/local/dir
	Windows  d:/Website/Dir
 */
function format_path($path = ''){
    if (empty($path)) {
        return $path;
    }
    $path = remove_backslash($path);
    $path = chg_slash($path);
    return $path;
}
/*
 * 单纯的对字符串进行提取（按照文件路径格式提取）
 * pathinfo 不支持中文！（php 7开始支持了）
 */
function get_file_info_from_str($str = ''){
    $str	   = chg_slash($str);
    $sIdx	   = mb_strrpos($str , '/');
    $sIdx	   = $sIdx === false ? 0 : $sIdx;
    $eIdx      = mb_strlen($str);
    $dirname   = mb_substr($str , 0 , $sIdx);
    $sepIdx    = mb_strrpos($str , '.');
    $filename  = mb_substr($str , $sIdx , $sepIdx);
    $extension = mb_substr($str , $sepIdx + 1 , mb_strlen($str));
    return array(
        'basename' => $filename . '.' . $extension ,
        'dirname'  => $dirname ,
        'filename' => $filename ,
        'extension'   => $extension
    );
}
/*
  * 获取文件信息
  * @param String $path 文件路径
  * @return Array
*/
function get_file_info($path = ''){
    $path = format_path($path);
    $path = gbk($path);
    if (!file_exists($path)) {
        return false;
    } else {
        if (is_dir($path)) {
            return false;
        } else {
            $filename  = get_filename($path);
            $size	   = filesize($path);
            $extension = get_extension($path);
            $mime	   = get_mime(utf8($path));
            $filename  = $filename === false  ? 'unknow' : $filename;
            $size	   = $size === false	  ? 'unknow' : $size;
            $extension = $extension === false ? 'unknow' : $extension;
            $mime      = $mime === false	  ? 'unknow' : $mime;
            return array(
                'filename'  => $filename  ,
                'size'      => $size      ,
                'extension' => $extension ,
                'mime'      => $mime
            );
        }
    }
}
// 获取文件名（URL || Local Path 都可，不检查文件是否存在）
function get_filename($path = ''){
    $path = format_path($path);
    $s_idx = mb_strrpos($path , '/');
    $s_idx = $s_idx === false ? 0 : $s_idx + 1;
    return mb_substr($path , $s_idx);
}
// 获取扩展名（URL || Local Path 都可）
function get_extension($path = ''){
    $path = format_path($path);
    $s_idx = mb_strrpos($path , '.');
    if ($s_idx !== false) {
        $s_idx += 1;
        return mb_substr($path , $s_idx);
    }
    return false;
}
/*
 * 获取文件 mime 信息
 * @param  String  文件路径
 * @return String
 */
function get_mime($file = ''){
    $file = format_path($file);
    $file = gbk($file);
    if (!file_exists($file)) {
        return false;
    }
    if (is_dir($file)) {
        return false;
    }
    $fres  = finfo_open(FILEINFO_MIME_TYPE);
    $mime  = finfo_file($fres , $file);
    finfo_close($fres);
    return $mime;
}
/*
 * 获取图片信息
 * @param String $file
 * @return Array
 */
function get_image_info($file = ''){
    $type_range = array('image/gif' , 'image/jpeg' , 'image/png' , 'image/swf');
    $info  = get_file_info($file);
    if (!$info) {
        return false;
    }
    $file  = gbk($file);
    $image = getimagesize($file);
    $info['width']  = $image['0'];
    $info['height'] = $image['1'];
    return $info;
}

/**
 * 安全载入文件（可避免重复载入文件）
 * @param Mixed(Array|String)
 * @return bool
 */
function load_files($files){
    $types = ['string' , 'array'];
    $arrs = [];
    if (!in_array(gettype($files) , $types)) {
        return false;
    }
    static $file_list = [];
    if (is_string($files)) {
        $arrs[] = $files;
    } else {
        $arrs = $files;
    }
    $filename_list = array_keys($file_list);
    foreach ($arrs as $v)
    {
        $v = format_path($v);
        $v = realpath($v);
        if (!in_array($v , $filename_list)) {
            $file_list[$v] = require gbk($v);
        }
    }
    return $file_list;
}

/****************************
 * author grayVTouch
 * 时间处理相关工具函数
 * ***************************
 */

/**
 * 检查时间
 * @param $time 待检查的时间字符串
 * @param $type 检查的时间类型，用于确定正则表达式
 */
function check_time($time , $type , $strict = true , $diy = ''){
    $type_range = ['year' , 'month' , 'date' , 'hour' , 'minute' , 'second'];

    if (!in_array($type , $type_range)) {
        return (bool) preg_match($diy , $time);
    }

    $strict = is_bool($strict) ? $strict : true;

    $append = $strict ? '' : '?';

    // 检索正则
    $year   = "/^\d{4}$/";
    $month  = "/^\d{4}\-(0{$append}[1-9]|1[0-2])$/";
    $date   = "/^\d{4}\-(0{$append}[1-9]|1[0-2])\-(0{$append}[1-9]|[1-2]\d|3[0-1])$/";
    $hour   = "/^\d{4}\-(0{$append}[1-9]|1[0-2])\-(0{$append}[1-9]|[1-2]\d|3[0-1]) (0{$append}\d|1[0-2])$/";
    $minute = "/^\d{4}\-(0{$append}[1-9]|1[0-2])\-(0{$append}[1-9]|[1-2]\d|3[0-1]) (0{$append}\d|1[0-2])\:(0{$append}\d|[1-5]\d)$/";
    $second = "/^\d{4}\-(0{$append}[1-9]|1[0-2])\-(0{$append}[1-9]|[1-2]\d|3[0-1]) (0{$append}\d|1[0-2])\:(0{$append}\d|[1-5]\d)\:(0{$append}\d|[1-5]\d)$/";

    return (bool) preg_match($$type , $time);
}

/*
 * 判断是否为闰年
 * 闰年判断规则
 * 1. 普通年能被4整除且不能被100整除的为闰年.
 * 2. 世纪年能被400整除的是闰年

 * 满足以上任一规则都是闰年
 */
function is_leap_year($year){
    return (($year % 4 === 0) && ($year % 100 !== 0)) || $year % 400 === 0;
}

/**
 * 获取指定单位的时间段的 秒数
 * year 以 365 天计
 * month 以 30 天计
 */
function time_convert($duration , $type){
    $type_range = ['year' , 'month' , 'day' , 'hour' , 'minute' , 'second'];

    if (!in_array($type , $type_range)) {
        throw new \Exception('参数 2 错误');
    }

    $second = 1;
    $minute = 60 * $second;
    $hour   = 60 * $minute;
    $day    = 24 * $hour;

    if ($type === 'year') return $duration * 365 * $day;
    if ($type === 'month') return $duration * 30 * $day;
    if ($type === 'day') return $duration * $day;
    if ($type === 'hour') return $duration * $hour;
    if ($type === 'minute') return $duration * $minute;
    if ($type === 'second') return $duration * $second;
}

/**
 * 获取指定日期的
 * 前后 n 年
 * 前/后 n 月
 * 前/后 n 天
 * 前/后 n 小时
 * 前/后 n 分钟
 * 前/后 n秒
 * @param $type
 * @param $time YYYY-MM-DD HH:II:SS
 * @param $diff 数字，允许正负数
 */
function diff_date($type , $time , $diff = 0){
    if (!check_time($time , $type)) {
        throw new \Exception("参数 1 错误");
    }

    $timestamp  = unix_timestamp($time);
    $duration   = time_convert($diff , $type);
    $timestamp += $duration;

    switch ($type)
    {
        case 'year':
            $format = 'Y';break;
        case 'month':
            $format = 'Y-m';break;
        case 'date':
            $format = 'Y-m-d';break;
        case 'hour':
            $format = 'Y-m-d H';break;
        case 'minute':
            $format = 'Y-m-d H:i';break;
        case 'second':
            $format = 'Y-m-d H:i:s';break;
    }

    return date($format , $timestamp);
}

/**
 * 将 unix 时间戳格式化显示
 * @param String $time
 */
function from_unixtime($time , $format = 'Y-m-d H:i:s'){
    return date($format , $time);
}

/**
 * 将格式化时间转换成 unix 时间戳
 * @param String $time
 */
function unix_timestamp($time){
    $info           = parse_time($time);
    $unix_timestamp = mktime($info['hour'] , $info['minute'] , $info['second'] , $info['month'] , $info['date'] , $info['year']);

    return $unix_timestamp;
}

/*
 * 根据生日计算得出年龄（周岁）
 * 时间格式：'YYYY-mm-dd HH:II:SS' or 'YYYY-mm-dd'
 * @param  String birthday 生日
 * @return 年龄 or 在生日时间大于当前时间的时候，返回 false
 */
function get_age($birthday = '') {
    $birthday_json   = parse_time($birthday);
    $cur_d			 = parse_time(date('Y-m-d'));

    if ($birthday_json === false || $cur_d === false) {
        return false;
    }

    $cur_year	 = $cur_d['year'];
    $cur_month	 = $cur_d['month'];
    $cur_date	 = $cur_d['date'];
    $year_diff	 = $cur_year  - $birthday_json['year'];
    $month_diff	 = $cur_month - $birthday_json['month'];
    $date_diff	 = $cur_date  - $birthday_json['date'];


    // 出生日期 大于 当前日期的时候返回 false
    if ($year_diff < 0 || $year_diff === 0 && $month_diff < 0  || $year_diff === 0 && $month_diff === 0 && $date_diff < 0) {
        return false;
    }

    // 未到月份
    if ($month_diff < 0) {
        $year_diff -= 1;
    }

    // 已到月份，未到日期
    if ($month_diff === 0 && $date_diff < 0) {
        $year_diff -= 1;
    }

    return $year_diff;
}


/*
 * 公历：获取某年某月的总天数
 * 1 3 5 7 8 10 12  都是31天
 * 4 6 9 11         都是30天
 * 2 月 平年28天 ， 闰年29天
 * 平年 365 天	 ， 闰年 366天
 */
function get_month_days($year = 0 , $month = 0){
    $to = [1 , 3 ,5 , 7 , 8 , 10 , 12];
    $tt = [4 , 6 , 9 , 11];

    if (in_array($month , $to , true)) {
        return 31;
    }

    if (in_array($month , $tt , true)) {
        return 30;
    }

    /*
     * 闰年判断规则
     * 1. 普通年能被4整除且不能被100整除的为闰年.
     * 2. 世纪年能被400整除的是闰年

     * 满足以上任一规则都是闰年
     */
    if (($year % 4 === 0 && $year % 100 !== 0) || $year % 400 === 0) {
        return 29;
    }

    return 28;
}

/**
 * 根据给定的时间，计算出距当前时间的间隔
 * 小于 1 min 的，单位 s
 * 小于 1 h 的，单位 min
 * 小于 1 d 的，单位 h
 * 小于 1 month 的，单位 d
 * 小于 1 year 的，单位 month
 * 大于 1 year 的，单位 year
 * @param Mixed   $time 时间点
 * @param String  $type 类型，支持的类型有：date、datetime、timestamp
 * date         2017-05-17
 * datetime     2017-05-17 18:00:04
 * timestamp    141444415454
 * @return  1 秒前 or 1 分钟前 or 1 小时前 ...
 *          1 秒后 or 1 分钟后 or 1 小时后 ...
 * 注意了：这边的月，30天算一月；这边的年，12个月算一年（360天）
 */
function get_time_diff($timestamp){
    if (!isset($timestamp)) {
        return false;
        // throw new \Exception('参数 1 未提供');
    }

    if (!is_valid($timestamp)) {
        return '无';
    }

    /*
    if (!isset($type)) {
        throw new \Exception('参数 2 未提供');
    }

    $type_range = ['date' , 'datetime' , 'timestamp'];

    if (!in_array($type , $type_range)) {
        throw new \Exception('参数 2 提供了一个不支持的类型！');
    }
    */

    $timeJson  = parse_time($timestamp);

    // 单位 s
    $timestamp = mktime($timeJson['hour'] , $timeJson['minute'] , $timeJson['second'] , $timeJson['month'] , $timeJson['date'] , $timeJson['year']);
    $cur_timestamp = time();

    $distance = $cur_timestamp - $timestamp;

    // 秒
    $second = 1;
    // 分
    $minute = $second * 60;
    // 时
    $hour = $minute * 60;
    // 天
    $date = $hour * 24;
    // 月（30天算1月）
    $month = $date * 30;
    // 年（12个月算1年）
    $year = $month * 12;


    if ($distance > 0) {
        if ($distance < $minute) {
            return $distance . ' 秒前';
        }

        if ($distance >= $minute && $distance < $hour) {
            return floor($distance / $minute) . ' 分钟前';
        }

        if ($distance >= $hour && $distance < $date) {
            return floor($distance / $hour) . ' 小时前';
        }

        if ($distance >= $date && $distance < $month) {
            $date = floor($distance / $date);

            if ($date == 1) {
                return '昨天';
            }

            if ($date == 2) {
                return '前天';
            }

            return $date . ' 天前';
        }

        if ($distance >= $month && $distance < $year) {
            return floor($distance / $month) . ' 月前';
        }

        if ($distance >= $year) {
            return floor($distance / $year) . ' 年前';
        }

        throw new \Exception('> 0 不在可控范围内的数值！');
    }

    if ($distance < 0) {
        $distance = abs($distance);

        if ($distance < $minute) {
            return $distance . ' 秒后';
        }

        if ($distance >= $minute && $distance < $hour) {
            return floor($distance / $minute) . ' 分钟后';
        }

        if ($distance >= $hour && $distance < $date) {
            return floor($distance / $hour) . ' 小时后';
        }

        if ($distance >= $date && $distance < $month) {
            $date = floor($distance / $date);

            if ($date == 1) {
                return '明天';
            }

            if ($date == 2) {
                return '后天';
            }

            return $date . ' 天后';
        }

        if ($distance >= $month && $distance < $year) {
            return floor($distance / $month) . ' 月后';
        }

        if ($distance >= $year) {
            return floor($distance / $year) . ' 年后';
        }

        throw new \Exception('< 0 不在可控范围内的数值！');
    }

    return '刚刚';
}

/*
 * 时间格式解析
 * 受支持的时间格式：
 *  数字型：14145455456
 *  字符串型：
 *      2017-07-15 => [
 *                      'year' => 2017 ,
 *                      'month' => 7 ,
 *                      'date' => 15 ,
 *                      'hour' => 0 ,
 *                      'minute' => 0 ,
 *                      'second' => 0
 *                    ]
 *      2017-07-15 15:15:00
 * @param Mixed $time 可以是 数字|字符串
 * @return Array 包含：年月日时分秒
 */
function parse_time($time){
    $number_reg = '/^\d+$/';
    $string_reg = '/\d+/';

    $parse = function($time = ''){
        $time = date('Y-m-d H:i:s' , $time);
        $data = explode(' ' , $time);
        $ymd  = explode('-' , $data[0]);
        $his  = explode(':' , $data[1]);

        return [
            'year'   => intval($ymd[0]) ,
            'month'  => intval($ymd[1]) ,
            'date'   => intval($ymd[2]) ,
            'hour'   => intval($his[0]) ,
            'minute' => intval($his[1]) ,
            'second' => intval($his[2])
        ];
    };

    if (preg_match($number_reg , $time) === 1) {
        // 数字型
        $time = intval($time);
        $time = $parse($time);
    } else {
        // 字符串型
        if (preg_match_all($string_reg , $time , $data) !== 0) {
            $data = $data[0];

            foreach ($data as $k => $v)
            {
                $data[$k] = intval($v);
            }

            $time = [
                'year'   => isset($data[0]) ? $data[0] : 1997 ,
                'month'  => isset($data[1]) ? $data[1] : 1 ,
                'date'   => isset($data[2]) ? $data[2] : 1 ,
                'hour'   => isset($data[3]) ? $data[3] : 0 ,
                'minute' => isset($data[4]) ? $data[4] : 0 ,
                'second' => isset($data[5]) ? $data[5] : 0
            ];
        } else {
            return false;
        }
    }

    return $time;
}

/*
 * 获取两个时间点的间隔，单位 s
 * 格式：2017-03-28 10:11:11
 * @param  Integer $s_time 开始时间
 * @param  Integer $e_time 结束时间
 * @return Integer 间隔
 */
function timestamp_diff($s_time , $e_time){
    $t1 = parse_time($s_time);
    $t2 = parse_time($e_time);

    $timestamp1 = mktime($t1['hour'] , $t1['minute'] , $t1['second'] , $t1['month'] , $t1['date'] , $t1['year']);
    $timestamp2 = mktime($t2['hour'] , $t2['minute'] , $t2['second'] , $t2['month'] , $t2['date'] , $t2['year']);

    return $timestamp2 - $timestamp1;
}

// 从格式化的时间中获取 unix 时间戳
function get_timestamp($format_time = ''){
    if (empty($format_time)) {
        throw new Exception('字符串为空!');
    }

    $check = preg_match('/^\d{4}-\d{2}-\d{2}( \d{2}:\d{2}:\d{2})?$/' , $format_time);

    if ($check == 0) {
        throw new Exception('参数 1 格式不正确');
    }

    $rel = explode(' ' , $format_time);
    $ymd = explode('-' , $rel[0]);
    $y   = intval($ymd[0]);
    $m   = intval($ymd[1]);
    $d   = intval($ymd[2]);

    if (isset($rel[1])) {
        $his = $rel[1];
        $h   = intval($his[0]);
        $i   = intval($his[1]);
        $s   = intval($his[2]);
    } else {
        $h = 0;
        $i = 0;
        $s = 0;
    }

    return mktime($h , $i , $s , $m , $d , $y);
}


/*
 * 根据给定的时间长度转化为预定义格式的时间
 * @param Number  $duration  时长，单位 s
 * @param String  $format    返回的时间格式
 * @param Boolean $isZeroAdd 分以上值为 0 是否需要严格按照格式进行返回，是 值为0的也会自动填充，否则不会
 * @return Mixed
 */
function format_time($duration = 0 , $format = 'D天H时I分S秒' , $isZeroAdd = true){
    $formatRange = array('D天H时I分S秒' , 'HH:II:SS' , 'json');
    $format		= !is_int(array_search($format , $formatRange)) ? 'D天H时I分S秒' : $format;
    $isZeroAdd	= !is_bool($isZeroAdd) ? true : $isZeroAdd;
    $sRatio      = 1;
    $iRatio		= 60;
    $hRatio		= 60 * 60;
    $dRatio		= 24 * 60 * 60;
    $getTime	= function($time , $ratio , $stepRatio){
        // 换算成同等单位
        $time = floor($time / $ratio);

        // 上一级长度
        $upTime = false;

        // 如果超过 步进 ，则获取上一级长度
        if ($time >= $stepRatio) {
            $upTime = floor($time / $stepRatio);
        }

        // 如果超过 步进 ，计算当前时间 - 上一级长度占据的时间 = 当前时间级别下的长度
        if ($upTime !== false) {
            $time -= $upTime * $stepRatio;
        }

        return $time;
    };

    $d = floor($duration / $dRatio);
    $h = $getTime($duration , $hRatio , 24);
    $i = $getTime($duration , $iRatio , 60);

    $costTime = $d * $dRatio + $h * $hRatio + $i * $iRatio;
    $s		  = $duration - $costTime;

    if ($format === 'D天H时I分S秒') {
        return $d . '天' . $h . '时' . $i . '分' . $s . '秒';
    }

    if ($format === 'HH:II:SS') {
        if ($isZeroAdd) {
            if ($h < 10) {
                $h = '0' . $h;
            }

            $h .= ':';

            if ($i < 10) {
                $i = '0' . $i;
            }

            $i.= ':';

            if ($s < 10) {
                $s = '0' . $s;
            }

            return $h . $i . $s;
        } else {
            $d = $d === 0 ? '' : $d . ':';
            $h = $h === 0 ? '' : $h . ':';

            if ($i < 10) {
                $i = '0' . $i;
            }

            $i .= ':';

            if ($s < 10) {
                $s = '0' . $s;
            }

            return $d . $h . $i . $s;
        }
    }

    if ($format === 'json') {
        return array(
            'day'	 =>  $d ,
            'hour'	 =>  $h ,
            'minute' =>  $i ,
            'second' =>  $s
        );
    }
}

// 获取指定月所在季度
function get_quarterly($month){
    $quarterly = [
        1 => [1 , 2 , 3] ,
        2 => [4 , 5 , 6] ,
        3 => [7 , 8 , 9] ,
        4 => [10 , 11 , 12]
    ];

    foreach ($quarterly as $k => $v)
    {
        if (in_array($month , $v)) {
            return $k;
        }
    }

    throw new \Exception("不支持的月份");
}

// 获取指定季度包含的月份
function get_month_for_quarterly($quarterly = 1){
    $range = [
        1 => [1 , 2 , 3] ,
        2 => [4 , 5 , 6] ,
        3 => [7 , 8 , 9] ,
        4 => [10 , 11 , 12]
    ];

    foreach ($range as $k => $v)
    {
        if ($k == $quarterly) {
            return $v;
        }
    }

    throw new \Exception("不支持的季度");
}

/**
 * 根据给定的时间，计算出距当前时间的间隔
 * 小于 1 min 的，单位 s
 * 小于 1 h 的，单位 min
 * 小于 1 d 的，单位 h
 * 小于 1 month 的，单位 d
 * 小于 1 year 的，单位 month
 * 大于 1 year 的，单位 year
 * @param Mixed   $time 时间点
 * @param String  $type 类型，支持的类型有：date、datetime、timestamp
 * date         2017-05-17
 * datetime     2017-05-17 18:00:04
 * timestamp    141444415454
 * @return  1 秒前 or 1 分钟前 or 1 小时前 ...
 *          1 秒后 or 1 分钟后 or 1 小时后 ...
 * 注意了：这边的月，30天算一月；这边的年，12个月算一年（360天）
 * time1 - time2 = duration
 * duration：time2 是在 time1 的 duration 前/后发生的
 */
function time_diff($time_1 , $time_2){
    $datetime_1 = is_string($time_1) ? date_create($time_1) : date_create()->setTimestamp($time_1);
    $datetime_2 = is_string($time_2) ? date_create($time_2) : date_create()->setTimestamp($time_2);
    $interval	= $datetime_1->diff($datetime_2);

    $suffix = $time_1 >= $time_2 ? '前' : '后';

    if (!empty($interval->y)) {
        return $interval->format("%Y年" . $suffix);
    }

    if (!empty($interval->m)) {
        return $interval->format("%m月" . $suffix);
    }

    if (!empty($interval->d)) {
        if ($interval->d == 1 && $time_1 > $time_2) {
            return "昨天";
        }

        if ($interval->d == 1 && $time_1 < $time_2) {
            return "后天";
        }

        return $interval->format("%d天" . $suffix);
    }

    if (!empty($interval->h)) {
        return $interval->format("%h小时" . $suffix);
    }

    if (!empty($interval->i)) {
        return $interval->format("%i分钟" . $suffix);
    }

    if (!empty($interval->s)) {
        return $interval->format("%s秒" . $suffix);
    }

    return '刚刚';
}