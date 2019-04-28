<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2019/1/9
 * Time: 9:59
 */

namespace app\mobile\model;

use think\Model;
use think\Model\Collection;

class Feedback extends Model
{
    // 单条：数据处理
    public static function single(Feedback $m)
    {
        if (is_null($m)) {
            return ;
        }
        // 这边做一些处理
    }

    // 多条：数据处理
    public static function multiple(Collection $res)
    {
        foreach ($res as $v)
        {
            self::single($v);
        }
    }
}