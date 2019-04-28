<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2018/10/30
 * Time: 11:42
 */

namespace app\mobile\model;

use think\Model;

class BankInfo extends Model
{
    // 单条：数据处理
    public static function single($obj){
        if (empty($obj)) {
            return ;
        }
    }

    // 多条：数据处理
    public static function multiple($objs){
        foreach ($objs as $v)
        {
            self::single($v);
        }
    }

    // 获取卡号
    public static function getCardNo($id){
        return self::get($id)->card_no;
    }

    // 模型关联：银行卡code
    public function bank()
    {
        return $this->belongsTo(BankCode::class , 'bank_code' , 'bank_code');
    }
}