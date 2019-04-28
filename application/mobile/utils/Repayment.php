<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2018/11/8
 * Time: 14:43
 *
 * 自动还款辅助类
 */

namespace app\mobile\utils;

use Exception;

/**
 * 创建计划的一些简单规则，必须严守！
 *
 * 1. 单次交易金额尽量不要太相近
 * 2. 代扣/代还 时间发生在 9 - 23
 * 3. 同一小时不能存在两笔记录（代扣/代还），意思是比如说上午 9:15发生了一笔交易，则9点-10点不能在产生第二笔交易！！且第二笔交易必须间隔在 40min 以后产生
 * 4. 单笔（含n次交易）：代扣记录中的代还时间 必须等于 代还记录中的代还时间；代还记录中的代扣金额 必须等于 0
 * 5. 单笔（含n次交易）：代扣记录中的交易金额 = 代扣金额 + 费率费用 + 单次交易手续费
 */

class Repayment
{
    // 消费类型
    public static $consumptionType = [];
    // 24时制：代扣/代还开始时间点
    public static $startTimePoint = 10;
    // 24时制：代扣结束时间点
    public static $endTimePoint = 22;
    // 24时制：代还开始时间点
    public static $startTimePointForRepayment = 9;
    // 24时制：代还结束时间点
    public static $endTimePointForRepayment = 23;
    // 单次交易的时间间隔，单位：min
    public static $transDuration = 40;

    /**
     * 生成还款计划
     * @param array $date                   包含具体还款日期的的数组
     * @param decimal $total                        总金额
     * @param int $per_repayment_amount         单笔还款金额
     * @param bool $advanced                是否高级（即：可选择消费区域/消费类型）
     * @param string $consumption_type      消费类型（前提：必须 $advanced = true）
     * @param decimal $rate                 通道费率
     * @return array
     */
    public static function planDetail(array $date , $total , $per_repayment_amount , $repay_count_for_day , $rate , $advanced = false , $consumption_type = ''){
        ini_set('memory_limit' , '2048M');
        // 每天还款笔数
        // $repay_count_for_day = config('app.repay_count_for_day');
        // 单笔交易次数
        $single_trans_count = config('app.single_trans_count');
        // 还款计划明细
        $list = [];
        // 已用金额
        $used_amount = 0;
        // 天数
        $count_for_day = count($date);
        // 总还款笔数
        $repay_count = $count_for_day * $repay_count_for_day;
        // 总还款次数
        $trans_count = $repay_count * $single_trans_count;
        $tmp_count = 1;
        // 单次交易服务费
        $service_fee = config('app.service_fee');
        // 单次交易最低额度
        $min_trans_money = config('app.min_trans_money');
        // 单次交易最低手续费
        $min_trans_fee = config('app.min_trans_fee');
        // 总的费率手续费
        $total_channel_fee = 0;
        // 总的手续费
        $total_fee = 0;
        // 还款笔数
        for ($i = 0; $i < count($date); ++$i)
        {
            $cur_date = $date[$i];
            // 一天中已经使用过的时间点
            $time_point = [];
            for ($m = 0; $m < $repay_count_for_day; ++$m)
            {
                // 记录总的手续费
                $total_fee += $service_fee;
                // 当前计划金额
                $money_for_plan = $tmp_count == $repay_count ? $total - $used_amount : $per_repayment_amount;
                // 单笔交易中已经使用的金额
                $used_money_for_each = 0;
                // 单笔交易
                $res = [
                    'id' => self::randomNum() ,
                    // 单笔计划还款时间 <=> 代还时间
                    'timestamp' => '' ,
                    'timestamp_explain' => '' ,
                    // 单笔流水号
                    'ordersn' => self::serialNumForOrder() ,
                    // 单笔还款金额
                    'amount' => $money_for_plan ,
                    // 费率手续费 = 代还金额（注意：不是代扣金额!!） * 通道费率
                    // 'rate_money' => fix_number($money_for_plan * $rate , 2) ,
                    // 单笔手续费（单次手续费 * 单笔交易次数）
                    'fix_amount' => $service_fee ,
                    // 明细
                    'detail' => [
                        // 代扣
                        'deduction' => [] ,
                        // 代还
                        'repayment' => null
                    ]
                ];
                // 单笔手续费：用以拆分单笔手续费
                // $single_fee = $res['rate_money'] + $res['fix_amount'];
                // 单笔手续费循环计算用
                // $used_fee = 0;
                // 使用过的代还时间点
                $deduction_time_point = [];
                // 生成代扣记录
                for ($n = 0; $n < $single_trans_count; ++$n)
                {
                    // 单次交易的可用手续费
                    // $balance_fee = $single_fee - $used_fee;
                    // 单次交易的可用额度
                    $balance = $money_for_plan - $used_money_for_each;
                    $detail = [];
                    // 生成默认的标识符（方便前端）
                    $detail['id'] = self::randomNum();
                    // 单次交易（扣款）：订单id
                    $detail['repay_orderid']          = self::orderNumForOrderDetail();
                    // 1-扣款 2-还款
                    $detail['repay_order_flag'] = 1;
                    // 代扣时间点
                    $detail['trade_time'] = self::randomDeductionTimePointForDay($cur_date , $time_point);
//                    $detail['trade_time'] = time();
                    $detail['trade_time_explain'] = date('H:i' , $detail['trade_time']);
                    // 代还时间点
                    $detail['transfer_time'] = $detail['trade_time'];
                    $detail['transfer_time_explain'] = $detail['trade_time_explain'];
                    // 记录一天中使用过的时间点
                    $time_point[] = $detail['trade_time'];
                    // 记录使用过的代扣时间点
                    $deduction_time_point[] = $detail['trade_time'];
                    // 代还金额
                    $detail['transfer_money'] = $money_for_plan;
                    // 单次交易金额，原始（未增加费率费用 + 单次交易手续费），不能低于 100
                    $plan_trade_money = $n == $single_trans_count - 1 ? $balance : self::random(1 , $balance , 2 , $min_trans_money);
                    $plan_trade_money = fix_number($plan_trade_money , 2);
                    // 单次交易费率手续费，必须四舍五入，精确到分
                    $rate_money = $plan_trade_money * $rate;
                    $rate_money *= 100;
                    $rate_money = round($rate_money);
                    $rate_money /= 100;
                    $rate_money = fix_number($rate_money , 2);

                    // trade_money = 计划分配金额 + 通道手续费
                    $detail['trade_money']      = fix_number($plan_trade_money + $rate_money , 2);
                    // 代还金额 = 计划分配金额（代扣模式中）
                    $detail['transfer_money']   = $plan_trade_money;
                    // 通道手续费
                    $detail['rate_money']       = $rate_money;

                    // 统计总的费率费用
                    $total_channel_fee += $rate_money;
                    // 增加费率费用（代扣金额 * 通道费率） + 单次交易手续费
                    // $allocate_fee = $n == $single_trans_count - 1 ? $balance_fee : self::random(0 , $balance_fee , 2 , $min_trans_fee);
                    // $detail['trade_money'] = fix_number($plan_trade_money + $allocate_fee , 2);

                    // 消费类型
                    // todo 调试期间，写死
//                    $detail['mcc'] = $advanced ? $consumption_type : self::randomSelectConsumptionType();
                    $detail['mcc'] = self::randomSelectConsumptionType();
                    $detail['mcc_name'] = get_correct_value('app.mcc' , $detail['mcc']);

//                    $detail['mcc'] = 11;
                    // $used_fee += $allocate_fee;
                    $used_money_for_each += $plan_trade_money;
//                  $detail = [
//                        'id'                        => $id ,
//                        'repay_orderid'             => $repay_orderid ,
//                        // 对应还款记录中的 repay_orderid
//                        'transfer_repay_orderid'    => null ,
//                        'repay_order_flag'          => $repay_order_flag ,
//                        'trade_money'               => $trade_money ,
//                        'trade_time'                => $trade_time ,
//                        'trade_time_explian'        => $trade_time_explain ,
//                        'act_amount'                => $act_amount ,
//                        'transfer_money'            => $transfer_money ,
//                        'transfer_time'             => $transfer_time ,
//                        'transfer_time_explain'     => null ,
//                        'mcc'                       => $mcc
//                    ];
                    // 新增扣款明细
                    $res['detail']['deduction'][] = $detail;
                }
                // 生成代还记录
                $repayment = [
                    // 生成默认的标识符（方便前端）
                    'id'                        => self::randomNum() ,
                    // 单次交易订单：id
                    'repay_orderid'             => self::orderNumForOrderDetail() ,
                    // 单次交易：还款类型的记录，不许要添加该字段
                    'transfer_repay_orderid'    => null ,
                    // 1-扣款 2-还款
                    'repay_order_flag'          => 2 ,
                    // 代扣金额
                    'trade_money'               => 0 ,
                    // 代扣时间
                    'trade_time'                => '' ,
                    'trade_time_explain'        => '' ,
                    // 实际代扣金额
                    'act_amount'                => 0 ,
                    // 代还金额
                    'transfer_money'            => 0 ,
                    // 代还时间点
                    'transfer_time'             => '' ,
                    'transfer_time_explain'     => '' ,
                    // 消费类型：字符串数字
                    // todo 如果接口测试通过，到时候再调整
                    'mcc'                       => 11
                ];

                // 代还记录中，代扣时间 <=> 代还时间
                $repayment['transfer_repay_orderid']= $repayment['repay_orderid'];
                $repayment['transfer_time']         = self::randomRepaymentTimePointForDay($cur_date , $time_point , $deduction_time_point);
                $repayment['transfer_time_explain'] = date('H:i' , $repayment['transfer_time']);
                $repayment['trade_time']            = $repayment['transfer_time'];
                $repayment['trade_time_explain']    = $repayment['transfer_time_explain'];

                $res['detail']['repayment'] = $repayment;
                $res['timestamp']           = $repayment['transfer_time'];
                //
                $res['timestamp_explain']   = date('Y-m-d' , $repayment['transfer_time']);

                // 代扣记录完善
                foreach ($res['detail']['deduction'] as &$v)
                {
//                    $v['transfer_time']          = $repayment['transfer_time'];
//                    $v['transfer_time_explain']  = $repayment['transfer_time_explain'];
                    $v['transfer_repay_orderid'] = $repayment['repay_orderid'];
                }
                // 记录交易次数
                $tmp_count++;
                $used_amount += $money_for_plan;
                $list[] = $res;
            }
        }
        // 总通道费率
        $total_channel_fee *= 100;
        $total_channel_fee = round($total_channel_fee);
        $total_channel_fee /= 100;
        $total_channel_fee = fix_number($total_channel_fee , 2);

        ini_set('memory_limit' , '256M');

        return [
            // 还款笔数
            'repay_count'   => $repay_count ,
            // 交易次数
            'trans_count'   => $trans_count ,
            // 通道手续费（合计）
            'channel_fee'   => $total_channel_fee ,
            // 手续费（合计）
            'fee'           => $total_fee ,
            // 还款计划列表
            'list'          => $list
        ];
    }

    // 返回随机数
    public static function randomNum(){
        return random(32 , 'mixed' , true);
    }

    // 随机选择消费类型
    public static function randomSelectConsumptionType(){
        $consumption_type = self::consumptionType();
        $min = 0;
        $max = count($consumption_type) - 1;
        $max = max(0 , $max);
        $index = rand($min , $max);
        $keys = array_keys($consumption_type);
        return $keys[$index];
    }

    // 获取消费类型
    public static function consumptionType(){
        return config('app.mcc');
    }

    // 获取消费类型

    /**
     * 生成代扣时间点
     * @param string $date
     * @param int $type repayment-代还 deduction-代扣
     * @param array $exclude 要排除的时间点
     * @return bool|false|float|int
     */
    public static function randomDeductionTimePointForDay($date = '' , array $exclude = []){
        $unix_timestamp = strtotime($date);
        if ($unix_timestamp === false) {
            return false;
        }
        // 单次交易之间的时间间隔
        $interval = self::$transDuration * 60;
        $s_duration = self::$startTimePoint * 3600;
        $e_duration = self::$endTimePoint * 3600;
        $duration   = $e_duration - $s_duration;
        $random     = rand(0 , $duration);
        $time_point = $unix_timestamp + $s_duration + $random;
        $time_point_explain = date('Y-m-d H' , $time_point);

        // 调试代码！！！！
        static $count = 1;
        if ($count++ > 200) {
            throw new Exception('陷入死循环');
        }

        foreach ($exclude as $v)
        {
            $cur_time_point_explain = date('Y-m-d H' , $v);
            if ($time_point_explain == $cur_time_point_explain) {
                // 同一小时区间，重新生成
                return self::randomDeductionTimePointForDay($date , $exclude);
            }
            // 检查是否间隔 >40 分钟
            if (abs($v - $time_point) <= $interval) {
                return self::randomDeductionTimePointForDay($date , $exclude);
            }
        }
        return $time_point;
    }

    // 生成代还时间点
    public static function randomRepaymentTimePointForDay($date = '' , array $exclude = [] , array $deduction_time_point = []){
        $unix_timestamp = strtotime($date);
        if ($unix_timestamp === false) {
            return false;
        }
        $max_deduction_time_point = max($deduction_time_point);
        // 单次交易之间的时间间隔
        $interval = self::$transDuration * 60;
        $s_duration = max($max_deduction_time_point - $unix_timestamp , self::$startTimePointForRepayment * 3600);
        $e_duration = self::$endTimePointForRepayment * 3600;
        $duration   = $e_duration - $s_duration;
        $random     = rand(0 , $duration);
        $time_point = $unix_timestamp + $s_duration + $random;
        $time_point_explain = date('Y-m-d H' , $time_point);
        // 调试代码！！！！
        static $count = 1;
        if ($count++ > 1000) {
            throw new Exception('陷入死循环');
        }
        foreach ($exclude as $v)
        {
            $cur_time_point_explain = date('Y-m-d H' , $v);
            if ($time_point_explain == $cur_time_point_explain) {
                // 同一小时区间，重新生成
                return self::randomRepaymentTimePointForDay($date , $exclude , $deduction_time_point);
            }
            // 检查是否间隔 >40 分钟
            if (abs($v - $time_point) <= $interval) {
                return self::randomRepaymentTimePointForDay($date , $exclude , $deduction_time_point);
            }
        }
        return $time_point;
    }

    // 返回制定范围的的随机数：包含小数点
    public static function random($min , $max , $fix_len = 2 , $limit_for_min = 0){
        $ratio  = rand(40 , 60) / 100;
        $range  = $max - $min;
        $res    = fix_number($range * $ratio , $fix_len);
//        // 调试代码！！！！
//        static $count = 0;
//        if ($count++ > 1000) {
//            throw new Exception("陷入死循环");
//        }
//        var_dump($range . ' ' . $res . ' = ' . $limit_for_min);
//        // 判断是否小于限制的最小值
//        if ($res < $limit_for_min) {
//            return self::random($min , $max , $fix_len , $limit_for_min);
//        }
        return $res;

    }

    // 生成单项计划订单号(cs_repayment_plan dsorderid)
    public static function orderNumForPlan(){
        return sprintf('xzm-rp-%s-%s' , date('YmdHis') , random(4 , 'number' , true));
    }

    // 生成单笔流水号(cs_repayment_order ordersn)
    public static function serialNumForOrder(){
        return sprintf('xzm-os-%s-%s' , date('YmdHis') , random(4 , 'number' , true));
    }

    // 生成单次交易订单号（cs_repayment_detail repay_orderid）
    public static function orderNumForOrderDetail(){
        return sprintf('xzm-ro-%s-%s' , date('YmdHis') , random(4 , 'number' , true));
    }

    // 生成单次交易订单号（cs_repayment_detail transfer_repay_orderid）
    public static function orderNumForOrderDetailOfTransfer(){
        return sprintf('xzm-tro-%s-%s' , date('YmdHis') , random(4 , 'number' , true));
    }

}