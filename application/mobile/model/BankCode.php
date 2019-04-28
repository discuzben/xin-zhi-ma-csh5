<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2018/11/19
 * Time: 14:18
 */

namespace app\mobile\model;

use think\Model;
use think\Model\Collection;

class BankCode extends Model
{
    // 信用卡账单
    public function creditCardBill(){
        return $this->hasMany(CreditCardBill::class , 'bank_code' , 'bank_code');
    }

    // 单条数据处理
    public static function single(BankCode $obj = null){
        if (empty($obj)) {
            return ;
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