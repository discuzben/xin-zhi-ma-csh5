<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2018/11/3
 * Time: 14:20
 */

namespace app\mobile\model;

use think\Model;
use think\Model\Collection;

class Role extends Model
{
    // 单条：数据处理
    public static function single(Role $obj = null){
        if (empty($obj)) {
            return ;
        }
        // 结算费率
        $obj->fee_rate_explain = number_format($obj->fee_rate / 100 , 2) . '%';
    }

    // 多条：数据处理
    public static function multiple(Collection $objs){
        foreach ($objs as $v)
        {
            self::single($v);
        }
    }

    // 关联用户模型
    public function user(){
        return $this->hasMany(User::class , 'user_id' , 'id');
    }
}