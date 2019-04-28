<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2018/11/9
 * Time: 15:52
 */

namespace app\mobile\model;

use think\Model;

class RepaymentPlan extends Model
{
    // 单条数据
    public static function single($obj){
        // 计划创建时间
        $obj->create_time_explain = date('Y-m-d H:i:s' , $obj->create_time);
        // 消费总金额（计划还款金额 + 费率手续费 + 单笔手续费合计）
        $obj->consumption_amount = '￥ ' . fix_number($obj->total_amount + $obj->fee + $obj->tip , 2);
        // 还款总金额（计划还款金额）
        $obj->repayment_amount = '￥ ' . $obj->total_amount;
        // 通道手续费
        $obj->channel_rate = fix_number($obj->fee_rate / 100 , 2) . '%';
        // 消费手续费
        $obj->consumption_fee = '￥ ' . $obj->fee;
        // 还款手续费
        $obj->repayment_fee = '￥ ' . $obj->tip;
        // 计划状态
        $obj->status_explain = get_correct_value('app.repayment_plan_status' , $obj->status);
    }

    // 多条数据处理
    public static function multiple($objs){
        foreach ($objs as $v)
        {
            self::single($v);
        }
    }
}