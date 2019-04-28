<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/29
 * Time: 16:35
 */

namespace app\http\middleware;

class MethodAuth
{
    public function handle($request, \Closure $next)
    {
        if (!$request->isPost()) {
            return c_response(5 , code_msg(5));
        }
        return $next($request);
    }
}