<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2018/11/23
 * Time: 14:29
 */

namespace app\mobile\model;

use think\Model;
use think\Model\Collection;

class RepaymentOrder extends Model
{
    // 单条数据
    public static function single(RepaymentOrder $obj){
        if (empty($obj)) {
            return ;
        }
        // 计划还款时间
        $obj->p_time_explain = date('Y-m-d H:i:s' , $obj->p_time);
        // 订单状态
        $obj->status_explain = get_correct_value('app.repayment_order_status' , $obj->status);
        // 计划金额
        $obj->a_amount = $obj->a_amount ?? 0;
        $obj->p_amount_explain = '￥' . $obj->p_amount;
        // 实际金额
        $obj->a_amount_explain = '￥' . fix_number($obj->a_amount , 2);
    }

    // 多条数据处理
    public static function multiple(Collection $objs){
        foreach ($objs as $v)
        {
            self::single($v);
        }
    }
}