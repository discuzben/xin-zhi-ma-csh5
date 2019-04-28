<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2018/12/19
 * Time: 16:00
 */

namespace app\mobile\controller;

use app\mobile\model\CreditCardBill;
use app\mobile\model\BankCode;
use app\mobile\model\BankInfo;

use app\mobile\utils\Page;

class Bill extends BaseController
{
    /**
     * @title 账单列表
     * @author by cxl
     *
     * @url /api/Bill/list
     * @method post
     *
     * @param name:limit type:string require:0 desc:返回记录数
     * @param name:page type:string require:0 desc:页数

     */
    public function billList()
    {
        $where = [
            ['user_id' , '=' , user()->uid]
        ];
        $count = CreditCardBill::where($where)->count();
        $page  = Page::deal($count);
        // 获取账单列表
        $res = CreditCardBill::with('bank')
            ->where($where)
            ->order('end_date' , 'desc')
            ->limit($page['offset'] , $page['limit'])
            ->select()
            ->each(function($v){
                BankCode::single($v->bank);
            });
        // 账单处理
        CreditCardBill::multiple($res);
        $res = Page::data($page , $res);
        return c_response(1 , '' , $res);
    }

    /**
     * @title 用户银行账单
     * @author by cxl
     *
     * @url /api/Bill/bankBill
     * @method post
     *
     * @param name:limit type:string require:0 desc:返回记录数
     * @param name:page type:string require:0 desc:页数

     */
    public function bankBill()
    {
        $count = BankInfo::where([
            ['uid' , '=' , user()->uid] ,
            ['card_type' , '=' , 1]
        ])->group('bank_code')
            ->count();
        $page = Page::deal($count);
        $bank_code = BankInfo::where([
            ['uid' , '=' , user()->uid] ,
            ['card_type' , '=' , 1]
        ])->group('bank_code')
            ->limit($page['offset'] , $page['limit'])
            ->column('bank_code');
        $res = BankCode::whereIn('bank_code' , $bank_code)->select();
        foreach ($res as $v)
        {
            BankCode::single($v);
            $v->bill = CreditCardBill::where([
                ['user_id' , '=' , user()->uid] ,
                ['bank_code' , '=' , $v->bank_code]
            ])->order('last_date' , 'desc')
                ->limit(1)
                ->find();
            if (!is_null($v->bill)) {
                CreditCardBill::single($v->bill);
            }
        }
        $res = Page::data($page , $res);
        return c_response(2 , '' , $res);
    }
}