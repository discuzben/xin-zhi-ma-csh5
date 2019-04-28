<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2018/11/2
 * Time: 10:46
 */

namespace app\mobile\utils;

use app\mobile\model\User;

class Tool
{
    /**
     * 更新 session 中缓存的用户信息
     * @param string $token 用户 token
     * @return bool
     */
    public static function updateUser($token = ''){
        $user = User::where('token' , $token)->find();
        if (is_null($user)) {
            return false;
        }
        $time = time();
        $duration = config('time.duration');
        if ($user->token_time + $duration < $time) {
            return false;
        }
        User::single($user);
        session('user' , $user);
        return true;
    }
}