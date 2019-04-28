<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2018/12/21
 * Time: 9:59
 */

namespace app\mobile\controller;

use app\mobile\model\User;
use think\Controller;

class Test extends Controller
{
    // 登录
    public function login()
    {
        $id = input('get.id');
        // 获取用户
        $user = User::with('role')
            ->where('uid' , $id)
            ->find();
        if (is_null($user)) {
            exit("未找到当前 id：{$id} 对应用户\n");
        }
        // 保存用户登录信息
        session('user' , $user);
        exit("用户登录成功");
    }

    public function  autoLogin()
    {
        // 获取用户
        $user = User::with('role')
            ->where('phone' , '15806020008')
            ->find();
        session('user' , $user);
        echo "<pre>";
        var_dump($user);
        exit("用户登录成功");
    }

}