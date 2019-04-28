<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2018/11/9
 * Time: 16:00
 */

namespace app\mobile\model;

use think\Model;
use think\Model\Collection;

class RepaymentDetail extends Model
{
    // 单条数据
    public static function single(RepaymentDetail $obj){
        if (empty($obj)) {
            return ;
        }
        // 订单状态
        $obj->status_explain = get_correct_value('app.repayment_order_status' , $obj->status);
        // 代扣/代还时间
        $obj->timestamp = $obj->repay_order_flag == 1 ? $obj->trade_time : $obj->transfer_time;
        $obj->timestamp = date('Y-m-d H:i:s' , $obj->timestamp);
        // 交易类型
        $obj->trans_type = get_correct_value('app.repayment_trans_type' , $obj->repay_order_flag);
        // 描述
        $obj->desc = $obj->repay_order_flag == 2 ? '还款计划' : sprintf('%s|%s' , '消费计划' , get_correct_value('app.mcc' , $obj->mcc));
        // 金额
        if ($obj->repay_order_flag == 1) {
            // 代扣
            $obj->money = $obj->trade_money;
        } else {
            // 代还
           $money = RepaymentOrder::where('id' , $obj->order_id)->value('a_amount');
           $obj->money = $money;
        }
    }

    // 多条数据处理
    public static function multiple(Collection $objs){
        foreach ($objs as $v)
        {
            self::single($v);
        }
    }
}