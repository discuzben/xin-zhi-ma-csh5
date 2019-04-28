<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2018/11/5
 * Time: 10:37
 */

namespace app\common\util;


class Hash
{
    /**
     * @title 生成密码
     * @author by cxl
     * @param $str 待加密的字符串
     *
     * @return string 加密后的字符串
     */
    public static function generate($str){
        return password_hash($str , PASSWORD_DEFAULT);
    }

    /**
     * @title 验证加密字符串
     * @author by cxl
     *
     * @param $str 原始未加密字符串
     * @param $compare 加密后的字符串
     * @return boolean
     */
    public static function verify($str , $compare){
        return password_verify($str , $compare);
    }
}