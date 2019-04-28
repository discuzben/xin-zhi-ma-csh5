<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2018/12/19
 * Time: 16:35
 */

namespace app\mobile\controller;

use Exception;
use Validate;
use Db;

use app\mobile\model\BankInfo;
use app\common\util\Hash;
use app\mobile\model\UserWithdraw;
use app\mobile\model\User;
use app\mobile\utils\Tool;
use app\mobile\model\UserIncome;
use app\mobile\utils\Page;

class Fund extends BaseController
{
    /**
     * @title 我的收益
     * @author by cxl
     */
    public function incomeView()
    {
        return view('income');
    }

    /**
     * @title 收益明细
     * @author by cxl
     */
    public function incomeDetailView()
    {
        return view('incomeDetail');
    }

    /**
     * @title 我的余额
     * @author by cxl
     */
    public function balanceView()
    {
        return view('balance');
    }

    /**
     * @title 余额提现
     * @author by cxl
     */
    public function withdrawView()
    {
        return view('withdraw');
    }

    /**
     * @title 余额提现
     * @author by cxl
     */
    public function withdrawLogView()
    {
        return view('withdrawLog');
    }


    /**
     * @title 资金提现（创建订单）
     * @author by cxl
     * @description 目前采取人工审核
     *
     * @url /api/Fund/withdraw
     * @method post
     *
     * @param name:coin require:1 type:string desc:提现金额
     * @param name:bank_card_id require:1 type:string desc:银行卡id
     * @param name:pay_password require:1 type:string desc:支付密码
     *
     * @return null:id
     */
    public function withdraw(){
        $data = request()->post();
        $validator = Validate::make([
            'coin' => 'require' ,
            'bank_card_id' => 'require' ,
            'pay_password' => 'require' ,
        ] , [
            'coin.require' => '请提供提现金额' ,
            'bank_card_id.require' => '请提供绑定的储蓄卡' ,
            'pay_password.require' => '请提供支付密码' ,
        ]);
        if (!$validator->check($data)) {
            return c_response('0001' , $validator->getError());
        }
        // 检查用户是否已经绑定储蓄卡
        $bank_card = BankInfo::get($data['bank_card_id']);
        if (is_null($bank_card)) {
            return c_response('0001' , '未找到您提供的储蓄卡相关信息');
        }
        if ($bank_card->card_type != 0) {
            return c_response('0001' , '请提供储蓄卡');
        }
        $data['coin'] = intval($data['coin']);
        // 最低提现金额
        $min_cash_withdrawal = config('app.min_cash_withdrawal');
        // 单笔提现收取手续费
        $service_fee = config('app.service_fee');
        // 检查金额是否大于指定金额
        if ($data['coin'] < $min_cash_withdrawal) {
            return c_response('0001' , sprintf("最低提现金额不得小于 %d元" , $min_cash_withdrawal));
        }
        // 检查用户余额是否足够
        if (user()->balance - $service_fee < $data['coin']) {
            return c_response('0001' , '用户余额不够');
        }
        // 检查支付密码是否正确
        if (!Hash::verify($data['pay_password'] , user()->pay_password)) {
            return c_response('0001' , '交易密码输入错误，请重新输入');
        }
        $data['uid'] = user()->uid;
        $data['real_name'] = $bank_card->user_name;
        $data['bank_name'] = $bank_card->bank_name;
        $data['bank_cardno'] = $bank_card->card_no;
        $data['create_time'] = time();
        $data['update_time'] = $data['create_time'];
        try {
            Db::startTrans();
            // 更新用户余额<=>可提现收益
            User::where('uid' , user()->uid)->dec('balance' , $data['coin']);
            // 更新累计提现收益（这个请在提现成功之后的回调中处理！！）
            Tool::updateUser(user()->token);
            $user_withdraw = new UserWithdraw();
            $user_withdraw->allowField([
                'uid' ,
                'coin' ,
                'real_name' ,
                'bank_name' ,
                'bank_cardno' ,
                'create_time' ,
                'update_time'
            ])->save($data);
            Db::commit();
            return c_response('0000' , '' , $user_withdraw->id);
        } catch (Exception $e) {
            Db::rollback();
            throw $e;
        }
    }

    /**
     * @title 收益明细（不分类）
     * @author by cxl
     * @description 收益明细，不区分分润 + 返佣
     *
     * @url /api/Fund/incomeDetail
     * @method post
     *
     * @return null:object
     */
    public function incomeDetail(){
        $count = UserIncome::where('uid' , user()->uid)->count();
        $page = Page::deal($count);
        $data = UserIncome::where('uid' , user()->uid)
            ->limit($page['offset'] , $page['limit'])
            ->order('create_time' , 'desc')
            ->select();
        UserIncome::multiple($data);
        // 安全的手机号码
        // todo 是否需要对获取的数据按照日期进行分组，这个看前端的要求，目前采取分组-参考金象万达
        $data = UserIncome::groupByDate($data);
        $res = Page::data($page , $data);
        return c_response('0000' , '' , $res);
    }

    /**
     * @title 收益明细（分类）
     * @author by cxl
     * @description 区分分润 + 返佣
     *
     * @url /api/Fund/incomeTypeDetail
     * @method post
     *
     * @param name:type type:string require:1 desc:收益类型
     *
     * @return null:object
     */
    public function incomeTypeDetail(){
        $data = request()->post();
        $validator = Validate::make([
            'type' => 'require'
        ] , [
            'type.require' => '请提供收益类型'
        ]);
        if (!$validator->check($data)) {
            return c_response('0001' , $validator->getError());
        }
        $where = [
            ['uid' , '=' , user()->uid] ,
            ['type' , '=' , $data['type']]
        ];
        $count = UserIncome::where($where)->count();
        $page = Page::deal($count);
        $data = UserIncome::where($where)
            ->order('create_time' , 'desc')
            ->limit($page['offset'] , $page['limit'])
            ->select();
        // 收入处理
        UserIncome::multiple($data);
        // 安全的手机号码
        // todo 是否需要对获取的数据按照日期进行分组，这个看前端的要求，目前采取分组-参考金象万达
        $data = UserIncome::groupByDate($data);
        $res = Page::data($page , $data);
        return c_response('0000' , '' , $res);
    }

    /**
     * @title 每日收益
     * @author by cxl
     * @description 每日收益
     *
     * @url /api/Fund/incomeForDay
     * @method post
     *
     * @param name:page type:string require:0 desc:页数
     *
     * @return null:object
     */
    public function incomeForDay(){
        $field = ['* , sum(income_amount) as amount' , 'date_format(create_time , "%Y-%m-%d") as date'];
        $where = [
            ['uid' , '=' , user()->uid]
        ];
        $group = 'date';
        $count = UserIncome::field($field)
            ->where($where)
            ->group($group)
            ->count();
        $page = Page::deal($count);
        $data = UserIncome::field($field)
            ->where($where)
            ->group($group)
            ->order('date' , 'desc')
            ->limit($page['offset'] , $page['limit'])
            ->select();
        // print_r($data);
        // 收入处理
        UserIncome::multiple($data);
        $res = Page::data($page , $data);
        return c_response('0000' , '' , $res);
    }

    // todo 单日收益明细
    public function incomeDetailForDay(){
        $data = request()->post();
        $validator = Validate::make([
            'date' => 'require'
        ] , [
            'date.require' => '日期必须提供'
        ]);
        if (!$validator->check($data)) {
            return c_response('0001' , $validator->getError());
        }
    }

    /**
     * @title 提现记录
     * @author by cxl
     * @description 提现记录
     *
     * @url /api/Fund/withdrawLog
     * @method post
     *
     * @return null:array
     */
    public function withdrawLog(){
        $data = request()->post();
        $validator = Validate::make([
            // 这边添加验证
        ] , [
            // 这边添加提示
        ]);
        if (!$validator->check($data)) {
            return c_response('0001' , $validator->getError());
        }
        $where = [
            ['uid' , '=' , user()->uid] ,
            ['isdelete' , '=' , 0]
        ];
        $count = UserWithdraw::where($where)
            ->count();
        $page = Page::deal($count);
        $res = UserWithdraw::where($where)
            ->limit($page['offset'] , $page['limit'])
            ->select();
        UserWithdraw::multiple($res);
        $res = Page::data($page , $res);
        return c_response('0000' , '' , $res);
    }
}