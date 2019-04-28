<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2018/11/5
 * Time: 15:49
 *
 * 分页处理，详细使用方法请在 doc/Page.md 中查看
 */

namespace app\mobile\utils;

class Page
{
    /**
     * @title 分页处理
     *
     * @param $count 总记录数
     *
     * @return null:object
     */
    public static function deal($count = 1){
        $method = strtolower(config('page.method'));
        $client = config('page.client');
        $page = $method == 'get' ? request()->get($client['page']) : request()->post($client['page']);
        $page = is_null($page) ? 1 : intval($page);
        $min_page = 1;
        $limit = $method == 'get' ? request()->get($client['limit']) : request()->post($client['limit']);
        $limit = is_null($limit) ? config('page.limit') : intval($limit);
        $field = config('page.field');

        $max_page = ceil($count / $limit);
        $max_page = max($min_page , $max_page);
        $page   = max($min_page , min($max_page , $page));
        $offset = max(0 , ($page - 1) * $limit);

        return [
            $field['min_page'] => $min_page ,
            $field['max_page'] => $max_page ,
            $field['page'] => $page ,
            $field['count'] => $count ,
            $field['limit'] => $limit ,
            $field['offset'] => $offset ,
        ];
    }

    /**
     * 生成返回给用户的数据
     */
    public static function data(array $page , $data){
        return array_merge($page , [
            'data' => $data
        ]);
    }
}