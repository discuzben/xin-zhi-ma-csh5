<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2018/11/6
 * Time: 16:09
 */

namespace app\mobile\model;

use think\Model;

class UserWithdraw extends Model
{
    // 单条：数据处理
    public static function single($obj){
        // 状态
        $obj->status_explain = get_correct_value('app.withdraw_status' , $obj->status);
        // 创建时间
        $obj->create_time_explain = date('Y-m-d H:i:s' , $obj->create_time);
        // 更新时间
        $obj->update_time_explain = date('Y-m-d H:i:s' , $obj->update_time);
        // 提现金额
        $obj->coin_explain = '-' . $obj->coin;
    }

    // 多条：数据处理
    public static function multiple($objs){
        foreach ($objs as $v)
        {
            self::single($v);
        }
    }
}