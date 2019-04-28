<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2018/12/1
 * Time: 10:31
 */

namespace app\mobile\model;

use think\Model;
use think\Model\Collection;

class Mailbox extends Model
{
    public static function single(Mailbox $obj = null)
    {
        if (empty($obj)) {
            return ;
        }
        // todo 做一些事情
    }

    // 单条数据处理
    public static function multiple(Collection $objs)
    {
        foreach ($objs as $v)
        {
            self::single($v);
        }
    }
}