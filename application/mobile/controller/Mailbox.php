<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2018/12/19
 * Time: 16:47
 */

namespace app\mobile\controller;

use Exception;
use Validate;

use app\mobile\model\Mailbox as MMailbox;
use app\mobile\model\UserMailbox;

class Mailbox extends BaseController
{
    /**
     * @title 邮箱列表
     * @author by cxl
     */
    public function mailboxView()
    {


        return view('mailbox');
    }

    /**
     * @title 选择邮箱
     * @author by cxl
     */
    public function selectMailboxView()
    {
        return view('selectMailbox');
    }

    /**
     * @title 添加邮箱
     * @author by cxl
     */
    public function addMailboxView()
    {
        return view('addMailbox');
    }

    /**
     * @title 获取邮件
     * @author by cxl
     */
    public function getMailboxView()
    {
        return view('getMailbox',["token"=>user()->token]);
    }


    /**
     * @title 新增电子邮件
     * @author by cxl
     */
    public function add(){
        $data = request()->post();
        $validator = Validate::make([
            'mailbox_id' => 'require' ,
            'protocol' => 'require' ,
            'username' => 'require' ,
            'password' => 'require' ,
        ] , [
            'mailbox_id.require'  => '请选择邮箱' ,
            'protocol.require' => '请选择邮箱协议' ,
            'username.require' => '请填写邮箱账号' ,
            'password.require' => '请填写邮箱密码（授权码）'
        ]);
        if (!$validator->check($data)) {
            return c_response('0001' , $validator->getError());
        }
        // 检查邮箱是否重复
        $count = UserMailbox::where('username' , $data['username'])->count();
        if ($count > 0) {
            return c_response('0001' , '该邮箱账号已经在使用，如果是您本人的邮箱，请直接选择该邮箱操作即可');
        }
        $data['user_id']  = user()->uid;
        $mailbox = new UserMailbox();
        $mailbox->allowField([
            'user_id' ,
            'mailbox_id' ,
            'protocol' ,
            'username' ,
            'password'
        ])->save($data);
        return c_response('0000' , '' , $mailbox->id);
    }

    /**
     * @title 支持的电子邮箱列表
     * @author by cxl
     * @description 支持的电子邮箱列表
     *
     * @url /api/Mail/support
     * @method post
     *
     * @return null:array
     */
    public function support(){
        $mailbox = MMailbox::select();
        MMailbox::multiple($mailbox);
        return c_response('0000' , '' , $mailbox);
    }

    /**
     * @title 我添加的邮箱列表
     * @author by cxl
     * @description 我添加的邮箱列表
     *
     * @url /api/Mail/my
     * @method post
     *
     * @return null:array
     */
    public function my(){
        $user_mailbox = UserMailbox::where('user_id' , user()->uid)->select();
        UserMailbox::multiple($user_mailbox);
        return c_response('0000' , '' , $user_mailbox);
    }

    /**
     * @title 删除已添加的电子邮件
     * @author by cxl
     * @description 删除已添加的电子邮件
     *
     * @url /api/Mail/del
     * @method post
     *
     * @return null:number
     */
    public function del(){
        $data = request()->post();
        $validator = Validate::make([
            'id_list' => 'require' ,
        ] , [
            'id_list.require' => '请选择要删除的邮箱'
        ]);
        if (!$validator->check($data)) {
            return c_response('0001' , $validator->getError());
        }
        $data['id_list'] = json_decode($data['id_list'] , true);
        if (empty($data['id_list'])) {
            return c_response('0001' , '请选择要删除的邮箱');
        }
        $res = UserMailbox::whereIn('id' , $data['id_list'])->delete();
        return c_response('0000' , '' , $res);
    }

}