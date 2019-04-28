<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2018/11/7
 * Time: 9:36
 */

namespace app\mobile\model;

use think\Model;
use think\Model\Collection;

class UserIncome extends Model
{
    // 字段重命名
    protected $field = [
        'type'          => 'income_type' ,
        'income_type'   => 'type' ,
    ];

    // 单条：数据处理
    public static function single(UserIncome $obj = null){
        if (empty($obj)) {
            return ;
        }
        // todo 如果后期数据都正确了，那么可以开放以下内容
        // 不安全：收益提供者手机
        $obj->phone = User::getPhone($obj->from_uid);
        // 安全：收益提供者手机
        $obj->safe_phone = User::safePhone($obj->phone);
        // 交易金额
        $obj->trade_amount_explain = number_format($obj->trade_amount , 2) . '元';
        // 收益金额
        $obj->income_amount_explain = number_format($obj->income_amount , 4) . '元';
        // todo 如果有不同年份的记录，需要在针对年份做一些处理
        // 收益日期
        $timestamp = strtotime($obj->create_time);
        $date       = date('Y-m-d' , $timestamp);
        $cur_date   = date('Y-m-d');
        $yesterday  = date_create('yesterday')->format('Y-m-d');
        if ($date == $cur_date) {
            $obj->date = '今天';
        } else if ($date == $yesterday) {
            $obj->date = '昨天';
        } else {
            $obj->date = date('m-d' , $timestamp);
        }
        // 收益时间
        $obj->time = date('H:i' , $timestamp);
        // todo 收益类型
        $obj->type_explain = get_correct_value('app.income_type' , $obj->type);
    }

    // 单条：数据处理
    public static function test(UserIncome $obj = null){

    }

    // 多条：数据处理
    public static function multiple(Collection $objs){
        foreach ($objs as $v)
        {
            self::single($v);
        }
    }

    // 对记录按照日期进行分组
    public static function groupByDate($objs){
        $res = [];
        foreach ($objs as $v)
        {
            $res[$v->date][] = $v;
        }
        $data = [];
        foreach ($res as $k => $v)
        {
            $data[] = [
                'date' => $k ,
                'list' => $v
            ];
        }
        return $data;
    }


}