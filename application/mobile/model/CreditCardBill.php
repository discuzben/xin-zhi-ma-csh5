<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2018/12/3
 * Time: 13:43
 */

namespace app\mobile\model;

use think\Model;
use think\Model\Collection;

class CreditCardBill extends Model
{
    // 银行卡代码
    public function bank(){
        return $this->belongsTo(BankCode::class , 'bank_code' , 'bank_code');
    }

    // 单条数据处理
    public static function single(CreditCardBill $obj = null){
        if (empty($obj)) {
            return ;
        }

        // 检查是否是本期账单
        $obj->bill_explain = $obj->last_date == date('Y-m-d') ? '本期账单' : $obj->last_date;
    }

    // 多条数据处理
    public static function multiple(Collection $objs){
        foreach ($objs as $v)
        {
            self::single($v);
        }
    }

    // 获取银行最新一封邮件
    public static function newest($user_id , $bank_code = '')
    {
        return self::where([
            ['user_id' , '=' , $user_id] ,
            ['bank_code' , '=' , $bank_code]
        ])->order('last_date' , 'desc')
            ->find();
    }
}