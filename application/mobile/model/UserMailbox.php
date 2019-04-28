<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2018/12/1
 * Time: 10:46
 */

namespace app\mobile\model;

use think\Model;
use think\Model\Collection;

class UserMailbox extends Model
{
    public static function single(UserMailbox $obj){

    }

    // 单条数据处理
    public static function multiple(Collection $objs){
        foreach ($objs as $v)
        {
            self::single($v);
        }
    }
}