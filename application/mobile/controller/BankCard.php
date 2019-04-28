<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2018/12/19
 * Time: 15:49
 */

namespace app\mobile\controller;

use Exception;
use Db;
use Validate;
use app\mobile\model\BankInfo;
use app\mobile\model\BankCode;

class BankCard extends BaseController
{
    /**
     * @title 添加信用卡
     * @author by cxl
     */
    public function addCreditCardView()
    {

        //$this->assign("user",session("user"));

        $user=user();
        return view("addCreditCard",["user"=>$user]);
    }

    /**
     * @title 添加储蓄卡
     * @author by cxl
     */
    public function addDebitCardView()
    {
        //$this->assign("user",session("user"));
        $user=session("user");
        return view('addDebitCard',["user"=>$user]);
    }

    /**
     * @title 我的银行卡
     * @author by cxl
     */
    public function myCardView()
    {
        return view('myCard');
    }


    /**
     * @title 添加信用卡
     * @author by cxl
     * @url /api/BankCard/addCreditCard
     * @method post
     * @group BankCard
     * @param name:card_no require:1 desc:卡号
     * @param name:security_code require:1 desc:安全码
     * @param name:expired_time require:1 过期时间
     * @param name:phone require:1 desc:银行预留手机
     *
     * @return null:id
     *
     */
    public function addCreditCard(){
        // 检查用户是否已经实名
        if (user()->real_name_status != 2) {
            return c_response('0001' , '用户尚未实名认证');
        }
        // 验证数据
        $data = request()->post();
        // 数据验证
        $validator = Validate::make([
            'card_no'       => 'require' ,
            'security_code' => 'require' ,
            'expired_time'  => 'require' ,
            'phone'         => 'require' ,
        ] , [
            'card_no.require'       => '银行卡号尚未提供' ,
            'security_code.require' => '安全码尚未提供' ,
            'expired_time.require'  => '安全码有效期尚未提供' ,
            'phone.require'         => '银行预留手机号尚未提供' ,
        ]);
        if (!$validator->check($data)) {
            return c_response('0001' , $validator->getError());
        }
        // 检查信用卡卡号是否重复
        $count = BankInfo::where('card_no' , $data['card_no'])->count();
        if ($count > 0) {
            return c_response('0001' , '你已经绑定该信用卡');
        }
        // todo 调用银联接口验证银行卡是否有效
        $data['uid'] = user()->uid;
//        $data['uid'] = 1;
        // 0 - 借记卡 1-信用卡
        $data['card_type'] = 1;

        try {
            Db::startTrans();
            // 检查是否之前申请过
            $bank_info = BankInfo::where('card_no' , $data['card_no'])->find();
            if (!is_null($bank_info)) {
                // 更新状态
                BankInfo::where('id' , $bank_info->id)->update([
                    'state' => 0 ,
                ]);
            } else {
                // 新增
                $bank_info = new BankInfo();
                // 插入信用卡
                $bank_info->allowField([
                    'uid' ,
                    'card_no' ,
                    'security_code' ,
                    'expired_time' ,
                    'phone' ,
                    'card_type'
                ])->save($data);
            }
            Db::commit();
            return c_response('0000' , '操作成功' , $bank_info->id);
        } catch(Exception $e){
            Db::rollBack();
            throw $e;
        }
    }

    /**
     * @title 添加储蓄卡
     * @author by cxl
     * @url /api/BankCard/addDebitCard
     * @method post
     * @group BankCard
     * @param name:card_no require:1 desc:卡号
     * @param name:phone require:1 desc:银行预留手机
     *
     * @return null:id
     *
     */
    public function addDebitCard(){
        // 检查用户是否已经实名
        if (user()->real_name_status != 2) {
            return c_response('0001' , '用户尚未实名认证');
        }
        // 检查当前用户是否已经绑定储蓄卡
        $count = BankInfo::where([
            ['uid' , '=' , user()->uid] ,
            ['card_type' , '=' , 0]
        ])->count();
        if ($count > 0) {
            return c_response('0001' , '您已经绑定过储蓄卡，不允许重复绑定');
        }
        // 验证数据
        $data = request()->post();
        // 数据验证
        $validator = Validate::make([
            'card_no'       => 'require' ,
            'phone'         => 'require' ,
        ] , [
            'card_no.require'       => '银行卡号尚未提供' ,
            'phone.require'         => '银行预留手机号尚未提供' ,
        ]);
        if (!$validator->check($data)) {
            return c_response('0001' , $validator->getError());
        }
        // 检查储蓄卡卡号是否重复
        $bank_card = BankInfo::where('card_no' , $data['card_no'])->find();
        if (!is_null($bank_card) && $bank_card->state == 1) {
            return c_response('0001' , '你已经绑定该储蓄卡');
        }
        $data['uid'] = user()->uid;
//        $data['uid'] = 1;
        // 0 - 借记卡 1-信用卡
        $data['card_type'] = 0;
        // 检查是否之前申请过
        if (!is_null($bank_card) && $bank_card->state == 0) {
            // 更新状态
            BankInfo::where('id', $bank_card->id)->update([
                'state' => 0,
            ]);
        } else {
            $bank_info = new BankInfo();
            // 插入信用卡
            $bank_info->allowField([
                'uid' ,
                'card_no' ,
                'phone' ,
                'card_type'
            ])->save($data);
        }
        return c_response('0000' , '' , $bank_info->id);
    }

    /**
     * @title 我的信用卡列表
     * @author by cxl
     * @description 我添加的信用卡列表
     *
     * @url /api/BankCard/creditCards
     * @method post
     *
     * @return null:object
     */
    public function creditCards(){
        $data = BankInfo::with('bank')
            ->where([
                ['uid' , '=' , user()->uid] ,
                ['card_type' , '=' , 1] ,
                ['state' , '=' , 0]
            ])->select();
        BankInfo::multiple($data);
        $data->each(function($v){
            BankCode::single($v->bank);
        });
        return c_response('0000' , '' , $data);
    }

    /**
     * @title 我的储蓄卡
     * @author by cxl
     * @description 我的储蓄卡
     *
     * @url /api/BankCard/debitCard
     * @method post
     *
     * @return null:object
     */
    public function debitCard(){
        $data = BankInfo::with('bank')
            ->where([
                ['uid' , '=' , user()->uid] ,
                ['card_type' , '=' , 0] ,
                ['state' , '=' , 0]
            ])->select();
        BankInfo::multiple($data);
        $data->each(function($v){
            BankCode::single($v->bank);
        });
        if (is_null($data)) {
            return c_response('0003' , lang('0003'));
        }
        return c_response('0000' , '' , $data);
    }

    /**
     * @title 修改信用卡信息
     * @author by cxl
     *
     * @url /api/BankCard/editCreditCard
     * @method post
     *
     * @param name:id type:string require:1 desc:信用卡id
     * @param name:security_code type:string require:1 desc:安全码
     * @param name:expired_time type:string require:1 desc:有效期
     * @param name:bill_day type:string require:1 desc:账单日
     * @param name:repayment_day type:string require:1 desc:还款日
     * @param name:credit_balance type:string require:1 desc:信用额度
     *
     */
    public function editCreditCard(){
        $data = request()->post();
        $validator = Validate::make([
            'id'            => 'require' ,
            'security_code' => 'require' ,
            'expired_time'  => 'require' ,
            'bill_day'      => 'require' ,
            'repayment_day' => 'require' ,
            'credit_balance' => 'require'
        ] , [
            'id.require'            => '请提供待修改的银行卡' ,
            'security_code.require' => '请提供安全码' ,
            'expired_time.require'  => '请提供安全码有效日期' ,
            'bill_day.require'      => '请提供账单日' ,
            'repayment_day.require' => '请提供还款日' ,
            'credit_balance.require' => '请提供信用卡额度' ,
        ]);
        if (!$validator->check($data)) {
            return c_response('0001' , $validator->getError());
        }
        // 检查是否存在卡
        $bank_info = BankInfo::where('id' , $data['id'])->find();
        if (is_null($bank_info)) {
            return c_response('0001' , '未找到提供的信用卡，请确认信用卡是否已经正确添加');
        }
        if ($bank_info->card_type != 1) {
            return c_response('0001' , '请提供信用卡！');
        }
        // todo credit_balance，请修正数据库错误字段
        $data['credit_blance'] = $data['credit_balance'];
        $bank_info->allowField([
            'security_code' ,
            'expired_time' ,
            'bill_day' ,
            'repayment_day' ,
            // todo credit_balance，请修正数据库错误字段
            'credit_blance'
        ])->save($data , [
            ['id' , '=' , $data['id']] ,
            ['card_type' , '=' , 1]
        ]);
        return c_response('0000' , '操作成功');
    }

    /**
     * @title 是否绑定储蓄卡
     * @author by cxl
     *
     * @url /api/BankCard/isBindDebitCard
     * @method post
     *
     * @return null:bind-已绑定|unbind-未绑定
     */
    public function isBindDebitCard(){
        $bank_info = BankInfo::where([
            ['uid' , '=' , user()->uid] ,
            ['card_type' , '=' , 0]
        ])->find();
        $bind = is_null($bank_info) ? 'unbind' : 'bind';
        return c_response('0000' , '' , $bind);
    }

    /**
     * @title 解绑储蓄卡
     * @author by cxl
     *
     * @url /api/BankCard/unbindDebitCard
     * @method post
     *
     * @param name:id type:string require:1 desc:储蓄卡id
     */
    public function unbindDebitCard()
    {
        $data = request()->post();
        $validator = Validate::make([
            'id' => 'require' ,
        ] , [
            'id.require' => '请选择储蓄卡'
        ]);
        if (!$validator->check($data)) {
            return c_response('0001' , $validator->getError());
        }
        $bank_info = BankInfo::where([
            ['id' , '=' , $data['id']] ,
            ['card_type' , '=' , 0]
        ])->find();
        if (is_null($bank_info)) {
            return c_response('0001' , '未找到储蓄卡信息，请确定您持有该卡');
        }
        if ($bank_info->uid != user()->uid) {
            return c_response('0001' , '你并未该卡的持有人，非法操作！');
        }
        $res = BankInfo::where('id' , $data['id'])->delete();
        if ($res) {
            return c_response('0000' , '操作成功');
        }
        return c_response('0001' , '操作失败');
    }

    /**
     * @title 信用卡信息
     * @author by cxl
     *
     * @url /api/BankCard/creditCard
     * @method post
     *
     * @param name:id type:string require:1 desc:信用卡id
     */
    public function creditCard()
    {
        $data = request()->post();
        $validator = Validate::make([
            'id' => 'require' ,
        ] , [
            'id.require' => '请提供信用卡'
        ]);
        if (!$validator->check($data)) {
            return c_response('0001' , $validator->getError());
        }
        $bank_info = BankInfo::with('bank')
            ->where([
                ['id' , '=' , $data['id']] ,
                ['card_type' , '=' , 1]
            ])->find();
        if (is_null($bank_info)) {
            return c_response('0001' , '未找到信用卡信息');
        }
        BankInfo::single($bank_info);
        BankCode::single($bank_info->bank);
        return c_response('0000' , '' , $bank_info);
    }
}