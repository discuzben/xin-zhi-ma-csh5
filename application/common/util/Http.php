<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2018/12/10
 * Time: 9:54
 *
 * 发送 http 请求
 */

namespace app\common\util;

use Exception;
use GuzzleHttp\Client;

class Http
{
    // 发送请求（同步）
    public static function request($method , $url , array $param = []){
        try {
            $http = new Client();
            $res  = $http->request($method , $url , [
                // get
            'query' => [
                // todo 以后或许用得着
            ] ,
                // post
                'form_params' => $param
            ]);
            return $res->getBody()->getContents();
        } catch(Exception $e) {
            return false;
        }
    }

    // post
    public static function post($url = '' , array $param = []){
        return self::request('post' , $url , $param);
    }

    // get
    public static function get($url = ''){
        return self::request('get' , $url);
    }

    // todo 异步请求
}