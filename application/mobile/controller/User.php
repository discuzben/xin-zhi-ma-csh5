<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2018/12/19
 * Time: 15:44
 */

namespace app\mobile\controller;

use Exception;
use Db;
use Validate;

// 工具库
use app\common\lib\UploadImage;
use app\common\util\Image;
use app\common\util\OCR;

use app\mobile\utils\Page;
use app\mobile\utils\Tool;

// 模型
use app\mobile\model\UserIdcardImg;
use app\mobile\model\User as MUser;
use app\mobile\model\RealnameAuthLog;
use app\mobile\model\Role;

class User extends BaseController
{
    /**
     * @title 我的保单
     * @author by cxl
     */
    public function myPolicyView()
    {
        return view('myPolicy');
    }

    /**
     * @title 功能列表
     * @author by cxl
     */
    public function functionView()
    {
        return view('function');
    }

    /**
     * @title 好友列表
     * @author by cxl
     */
    public function friendView()
    {
        return view('friend' , [
            'id' => user()->uid
        ]);
    }

    /**
     * @title 实名认证
     */
    public function realNameView()
    { 
        if (user()->real_name_status == 2) {
            return view('user' , [
                'user' => user()
            ]);
        }
        return view('realName');
    }

    /**
     * @title 实名认证
     * @author by cxl
     * @url /api/User/realnameAuth
     * @method post
     *
     * @param name:real_name require:1 desc:真实姓名
     * @param name:idcard_no require:1 desc:身份证号
     * @param name:image require:1 desc:图片字段
     *
     */
    public function realnameAuth(){
        if (user()->real_name_status == 2) {
            // 已经实名
            return c_response('0001' , config('app.realname_auth_status.2'));
        }
        $data = request()->post();
        $validator = Validate::make([
            'real_name'  => 'require' ,
            'idcard_no' => 'require'
        ], [
            'real_name.require' => '真实姓名必须提供' ,
            'idcard_no.require' => '卡号必须提供' ,
        ]);
        if (!$validator->check($data)) {
            return c_response('0001' , $validator->getError());
        }
        // 检查图片是否上传
        $image = isset($_FILES['image']) ? $_FILES['image'] : [];
        if (UploadImage::count($image) != 2) {
            return c_response('0001' , '请正确上传三张图片');
        }
        $res = Image::multiple($image);
        if ($res['status'] == 'error') {
            return c_response('0001' , $res['msg']);
        }
        $images = $res['data']['success'];
        // 检查是否三张图片都已经成功上传完成
        if (!(count($images) == 2)) {
            return c_response('0001' , '上传图片发生某些错误导致部分图片丢失，请稍后再试');
        }
        try {
            Db::startTrans();
            // 检查是否之前已经认证过
            $user_idcard_img = new UserIdcardImg();
            if (empty(user()->idcard_img_id)) {
                // 之前未认证过，新增
                $user_idcard_img->save([
                    'idcard_img1' => $images[0]['url'] ,
                    'idcard_img2' => $images[1]['url'] ,
                ]);
            } else {
                // 之前认证过，更新
                $user_idcard_img->save([
                    'idcard_img1' => $images[0]['url'] ,
                    'idcard_img2' => $images[1]['url'] ,
                ] , [
                    'id' => user()->idcard_img_id
                ]);
                $user_idcard_img->id = user()->idcard_img_id;
            }
            // 识别正面图片
            $front = base64_encode(file_get_contents($images[0]['path']));
            $back  = base64_encode(file_get_contents($images[1]['path']));
            $front_res = OCR::determine($front , 'front');
            $back_res  = OCR::determine($back , 'back');
            // 检查是否和图片上的信息一致
            if ($front_res['status'] && $back_res['status'] && $front_res['result']['realname'] == $data['real_name'] && $front_res['result']['idcard'] == $data['idcard_no']) {
                // 认证成功
                $data['real_name_status'] = 2;
            } else {
                // 未实名
                $data['real_name_status'] = 9;
            }
            // 保存到实名日志
            $auth_log = new RealnameAuthLog();
            $auth_log->saveAll([
                // 反面识别结果
                [
                    'begin'     => $back_res['result']['begin'] ,
                    'department' => $back_res['result']['department'] ,
                    'end'       => $back_res['result']['end'] ,
                    'order_id'  => $back_res['result']['orderid'] ,
                    'reason'    => $back_res['reason'] ,
                    'error_code' => $back_res['error_code'] ,
                    'side' => 'back' ,
                    'status' => $back_res['status'] ? 'y' : 'n'
                ] ,
                // 正面识别结果
                [
                    'realname'  => $front_res['result']['realname'] ,
                    'sex'       => $front_res['result']['sex'] ,
                    'nation'    => $front_res['result']['nation'] ,
                    'born'      => $front_res['result']['born'] ,
                    'address'   => $front_res['result']['address'] ,
                    'idcard'    => $front_res['result']['idcard'] ,
                    'order_id'  => $front_res['result']['orderid'] ,
                    'reason'    => $front_res['reason'] ,
                    'error_code' => $front_res['error_code'] ,
                    'side' => 'front' ,
                    'status' => $front_res['status'] ? 'y' : 'n'
                ] ,
            ]);
            $data['idcard_img_id'] = $user_idcard_img->id;
            $user = new MUser();
            $user->allowField([
                'real_name' ,
                'idcard_no' ,
                'idcard_img_id' ,
                'real_name_status' ,
            ])->save($data , [
                'uid' => user()->uid
            ]);
            Db::commit();
            // 更新 session 缓存数据
            if (!Tool::updateUser(user()->token)) {
                throw new Exception("非法操作");
            }
            return c_response('0000' , '' , $data['real_name_status']);
        } catch(Exception $e) {
            Db::rollback();
            throw $e;
        }
    }


    /**
     * @title 好友列表（推广人数-分类）
     * @author by cxl
     * @description 好友列表（推广人数）
     *
     * @url /api/User/friendCount
     * @method post
     *
     * @return service: object
     * @return broker: object
     * @return general: object
     */
    public function friendCount(){
        $id     = user()->uid;
        $direct = MUser::directTypeData($id);
        $indirect = MUser::indirectTypeData($id);
        return c_response('0000' , '' , [
            'service' => [
                'direct'    => $direct['service'] ,
                'indirect'  => $indirect['service'] ,
            ] ,
            'broker' => [
                'direct'    => $direct['broker'] ,
                'indirect'  => $indirect['broker'] ,
            ] ,
            'general' => [
                'direct'    => $direct['general'] ,
                'indirect'  => $indirect['general'] ,
            ] ,
        ]);
    }

    /**
     * @title 好友列表（推广人数-总计）
     * @author by cxl
     * @descrition 好友列表（推广人数-总计）
     *
     * @url /api/User/friendTotalCount
     * @method post
     *
     * @return direct:int
     * @return indirect:int
     */
    public function friendTotalCount(){
        $id = user()->uid;
        $data = [
            'direct' => MUser::directUserCount($id) ,
            'indirect' => MUser::indirectUserCount($id) ,
        ];
        return c_response('0000' , '' , $data);
    }

    /**
     * @title 好友列表（用户详情）
     * @author by cxl
     * @description 好友列表（用户详情）
     *
     * @url /api/User/friends
     * @method post
     *
     * @param name:id type:string require:1 desc: 用户id
     * @param name:page type:int require:0 desc:页数
     * @param name:limit type:int require:0 desc:每页记录数
     *
     * @return null:array
     */
    public function friends(){
        $data = request()->post();
        $validator = Validate::make([
            'id' => 'require' ,
        ] , [
            'id.require' => '请选择用户'
        ]);
        if (!$validator->check($data)) {
            return c_response('0001' , $validator->getError());
        }
        $count = MUser::where([
            ['p_uid' , '=' , $data['id']]
        ])->count();
        $page = Page::deal($count);
        // 获取对应的用户角色数据（直接推广用户）
        $res = MUser::with('role')
            ->withSum(['userIncome' => 'income_amount'] , 'income_amount')->where([
                ['p_uid' , '=' , $data['id']] ,
            ])->limit($page['offset'] , $page['limit'])
            ->select();
        MUser::multiple($res);
        $res->each(function($v){
            Role::single($v->role);
        });
        $res = Page::data($page , $res);
        return c_response('0000' , '' , $res);
    }

    /**
     * @title 获取登录用户信息
     * @author by cxl
     * @description 获取登录用户信息
     *
     * @url /api/User/user
     * @method post
     *
     * @return null:object
     */
    public function user(){
        $res = MUser::with('role')
            ->where('uid' , user()->uid)
            ->find();
        MUser::single($res);
        Role::single($res->role);
        return c_response('0000' , '' , $res);
    }

    /**
     * 显示会员升级页面；
     */
    public  function  upgradeView()
    {

        $user=user();

        return view("upgrade",["token"=>$user->token,"openid"=>session("openid")]);
    }


}