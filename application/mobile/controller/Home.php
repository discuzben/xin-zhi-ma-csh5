<?php
/**
 * Created by PhpStorm.
 * User: leo
 * Date: 2018/12/18
 * Time: 11:23
 */

namespace app\mobile\controller;


class Home extends WebBase
{
    function  daiKuang()
    {
        return view("daikuang");
    }


    /*
     * 信用卡申请
     */
    function  xinYongKa_sq()
    {
        return view("xinyongka_sq");

    }


    function weixinlogin()
    {

    }

    /**
     * @title 信用分
     * @author by cxl
     */
    public function creditScoreView()
    {
        return view('creditScore');
    }

    /**
     * @title 建议反馈
     * @author by cxl
     */
    public function feedbackView()
    {
        return view('feedback');
    }

    /**
     * @title 唯品会
     * @author by cxl
     *
     */
    public function vipshopView()
    {
        return view('vipshop');
    }

    /**
     * @title 商户入驻
     * @author by cxl
     *
     */
    public function supplierView()
    {
        return view('supplier');
    }

}