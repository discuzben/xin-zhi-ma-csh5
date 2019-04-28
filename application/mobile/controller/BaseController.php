<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2018/12/20
 * Time: 9:36
 */

namespace app\mobile\controller;

use think\Controller;
use Exception;
use app\common\util\Exception as MyException;

class BaseController extends Controller
{
//    protected $middleware = ['MethodAuth' , 'UserAuth'];
    protected $middleware = ['UserAuth'];

    public function __construct()
    {
        parent::__construct();
        // todo 这边做一些共享的事情

        /**
         * 自定义异常处理器
         * by cxl
         * todo 将要上线的时候请移除这些
         */
//        $mode = config('app.mode');
//
//        switch ($mode)
//        {
//            case 'development':
//                set_exception_handler([MyException::class, 'debug']);
//                break;
//            case 'production':
//                set_exception_handler([MyException::class , 'product']);
//                break;
//            default:
//                throw new Exception("不支持的系统模式");
//        }
    }
}