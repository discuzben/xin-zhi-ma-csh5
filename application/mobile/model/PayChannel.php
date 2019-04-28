<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2018/11/5
 * Time: 14:11
 */

namespace app\mobile\model;

use think\Model;

class PayChannel extends Model
{
    public static function single($obj){
        // 通道名称
        $obj->channel_name = sprintf("%s（%s）" , $obj->pay_channel_name , $obj->comment);
        // 代还费率
        $obj->ratio = number_format($obj->channel_user_rate / 100 , 2) . '%';
        // 单笔限额
        $obj->limit = sprintf("%d-%d" , $obj->limit_amount_min , $obj->limit_amount_max);
    }

    public static function multiple($objs){
        foreach ($objs as $v)
        {
            self::single($v);
        }
    }
}