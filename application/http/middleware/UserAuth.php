<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/29
 * Time: 16:35
 */

namespace app\http\middleware;

use app\mobile\utils\Tool;

class UserAuth
{
    // 要排除用户登录状态的认证
    protected $_exclude = [];

    public function handle($request, \Closure $next)
    {
        if (!$this->auth($request)) {
            return c_response('0004' , lang('0004'));
        }
        return $next($request);
    }

    // 用户认证
    public function auth($q){
        $path = $q->path();

        if (in_array($path , $this->_exclude)) {
            return true;
        }
        // 检查是否存在 session
        if (!is_null(session('user'))) {
            return true;
        }


    }
}