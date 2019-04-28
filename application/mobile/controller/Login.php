<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2018/11/29
 * Time: 9:55
 *
 *
 */

namespace app\mobile\controller;

use think\Controller;
use think\Db;
use think\facade\Validate;


use app\mobile\model\User;

use app\common\util\Hash;
use app\common\util\Sms;


class Login extends Controller
{
    // 一定要覆盖中间件
    protected $middleware = [];

    // 用户注册界面
    public function registerView(){
        return view('register');
    }

    /**
     * @title 用户注册
     * @author by cxl
     * @description 用户注册
     * @url /api/Login/register
     * @method post
     *
     * @param name:phone type:string require:1 desc:手机号码
     * @param name:vcode type:string require:1 desc:验证码
     * @param name:password type:string require:1 desc:密码
     * @param name:confirm_password type:string require:1 desc:确认密码
     * @param name:invite_user_phone type:string require:1 desc:邀请人手机号码
     *
     * @return null:id
     */
    public function register(){
        $data = request()->post();
        $validator = Validate::make([
            'phone' => 'require' ,
            'vcode' => 'require' ,
            'password' => 'require' ,
            'confirm_password' => 'require' ,
            'pro_code'  => 'require' ,
//            'province'  => 'require' ,
//            'city'  => 'require' ,
//            'area'  => 'require' ,
//            'longitude'  => 'require' ,
//            'latitude'  => 'require' ,
        ] , [
            'phone.require' => '手机号码必须提供' ,
            'vcode.require' => '验证码必须提供' ,
            'password.require' => '密码必须提供' ,
            'confirm_password.require' => '确认密码必须提供' ,
            'pro_code.require' => '邀请码必须提供' ,

//            'province.require' => '省份必须提供' ,
//            'city.require' => '城市必须提供' ,
//            'area.require' => '区域必须提供' ,
//            'longitude.require'  => '经度必须提供' ,
//            'latitude.require'  => '纬度必须提供' ,
        ]);
        if (!$validator->check($data)) {
            return c_response('0001' , $validator->getError());
        }
        // 检查是否已经注册
        $count = User::where('phone' , $data['phone'])->count();
        if ($count > 0) {
            return c_response('0001' , '用户已经注册，请登录');
        }
        // 检查两次输入密码是否正确
        if ($data['password'] != $data['confirm_password']) {
            return c_response('0001' , '两次输入的密码不一样！请重新输入');
        }
        // todo 检查短信验证码这个操作逻辑可能会和现在不一样
        // 检查验证码是否正确
        $code = session('invite_register_code');
        if (is_null($code)) {
            return c_response('0001' , '请先获取验证码');
        }
        // 调试阶段不设置验证码时效
        $duration = config('time.code_duration');
        if (time() > $code['time'] + $duration) {
            return c_response('0001' , '验证码已经失效，请重新发送');
        }
        // 检查接收验证码的手机号码和当前号码是否一致
        if ($code['phone'] != $data['phone']) {
            return c_response('当前提供手机号码和接收验证码的手机号码不一致');
        }
        // 判断验证码是否正确
        if ($data['vcode'] != $code['code']) {
            return c_response('0001' , '验证码错误');
        }
        // 检查是否存在该邀请人
        $invite_user = User::where('pro_code' , $data['pro_code'])->find();
        if (is_null($invite_user)) {
            return c_response('0001' , '未找到当前提供邀请码对应的用户');
        }
        // 检查注册手机号码和邀请人手机号码是否一致
        if ($data['phone'] == $invite_user->phone) {
            return c_response('0001' , '注册人手机号码和邀请人手机号码一致，请重新填写');
        }
        // 上级用户id，也是代理id
        $data['password'] = Hash::generate($data['password']);
        $data['p_uid']  = $invite_user->uid;
        // 注册的时候，还要将各类上级都添加上去
        $ps_uid = explode(',' , $invite_user->ps_uid);
        $ps_uid = filter_arr($ps_uid);
        $data['reg_ip'] = ip2long($_SERVER['REMOTE_ADDR']);
        // todo 根据每个用户生成 sign
        $data['sign'] = ssl_random(32);
        $data['create_time'] = time();
        // 生成邀请码
        $data['pro_code'] = substr(md5($data['phone']) , 0 , 16);

        $data['province']   = $data['province'] ?? 'unknow';
        $data['city']       = $data['city'] ?? 'unknow';
        $data['area']       = $data['area'] ?? 'unknow';

        $data['reg_province'] = $data['province'];
        $data['reg_city'] = $data['city'];
        $data['reg_district'] = $data['area'];
        try {
            Db::startTrans();
            $user = new User();
            $user->allowField([
                'phone' ,
                'password' ,
                'p_uid' ,
                'ps_uid' ,
                'reg_ip' ,
                'reg_city' ,
                'sign' ,
                'pro_code' ,
                'reg_province' ,
                'reg_city' ,
                'reg_district' ,
                'create_time' ,
                'longitude' ,
                'latitude'
            ])->save($data);
            // 更新用户
            $ps_uid[] = $user->uid;
            $user->ps_uid = implode(',' , $ps_uid);
            $user->save();
            Db::commit();
            // 删除掉验证码
            session('invite_register_code' , null);
            return c_response(1 , '注册用户成功' , $user->uid);
        } catch(Exception $e) {
            Db::rollBack();
            throw $e;
        }
    }

    /**
     * @title 发送注册验证码
     * @author by cxl
     *
     * @url /api/Login/registerVerifyCode
     * @method post
     *
     * $param name:phone type:string require:1 desc:手机号码
     */
    public function registerVerifyCode(){
        $data = request()->post();
        $validator = Validate::make([
            'phone' => 'require' ,
        ] , [
            'phone.require' => '请提供手机号码'
        ]);
        if (!$validator->check($data)) {
            return c_response('0001' , $validator->getError());
        }
        // 检查手机号码格式
        if (!check_phone($data['phone'])) {
            return c_response('0001' , '手机号码格式错误');
        }
        $duration   = config('time.code_interval');
        $code       = session('invite_register_code');
        // 检查间隔时间
        if (!is_null($code) && (time() - $code['time'] <= $duration)) {
            return c_response('0001' , '发送过于频繁');
        }
        // 检查是否已经注册
        $count = User::where('phone' , $data['phone'])->count();
        if ($count > 0) {
            return c_response('0001' , '用户已经注册，请登录');
        }
        $time = time();
        $code = random(4 , 'number' , true);
        // 生产阶段
        Sms::register($data['phone'] , $code);
        session('invite_register_code' , [
            // 发送时间
            'time' =>  $time ,
            // 展示
            'timestamp' => date('Y-m-d H:i:s' , $time) ,
            // 发送的验证码
            'code' => $code ,
            // 手机号码
            'phone' => $data['phone']
        ]);
        return c_response('0000' , '发送验证码成功');
    }


    /**
     * 微信自动登录时的绑定手机号，发送验证码
     */
    public  function  SendSmsCode()
    {
        $data = input('post.');
        $time=time();

        //验证
        if(!$data || !$data["phone"] || strlen($data["phone"])!=11)
        {
            c_response(0,"手机号码有误");
        }

        //控制不要频繁发送短信；
        $duration   = config('time.code_interval');
        $code       = session('invite_register_code');
        // 检查间隔时间
        if (!is_null($code) && (time() - $code['time'] <= $duration)) {
            return c_response('0001' , '发送过于频繁');
        }


        $code = random(6 , 'number' , true);
        //Sms::register($data['phone'] , $code);

        //写入session
        session('wx_bindphone_code' , [
            // 发送时间
            'time' =>  $time,
            // 展示
            'timestamp' => date('Y-m-d H:i:s' , $time) ,
            // 发送的验证码
            'code' => $code ,
            // 手机号码
            'phone' => $data['phone']
        ]);
        return c_response('0000' , '发送验证码成功'.$code);
    }


    /**
     * 微信自动注册用户；
     */
    public  function  registerWeixin()
    {

        $weixinInfo=session("wx_login_info");

       //var_dump($weixinInfo);

        $vcode=input("post.vcode");
        $thistime=time();
        //验证验证码；
        $code=session("wx_bindphone_code");

        //var_dump($code);


        if($vcode!=$code["code"])
        {
            return c_response('0001' , "验证码有误！".$vcode.",".$code["code"]);
        }
        if(($code["time"]+300)<$thistime)
        {
            return c_response('0001' , "验证码已经过期！");
        }


        //1.如果手机号已经有用户， 把微信相关信息写入此用户中；
        //2.如果手机号没有用户， 新生成一个用户，写入相关信息；
        $rs=$this->bindPhoneDo($code["phone"],$weixinInfo);


        if($rs)
        {
            return c_response('0000' , '用户注册完成');
        }
        else
        {
            return c_response('0001' , '注册失败');
        }

    }

    /**
     * 判断用户是否已经存在
     */
    function isExistPhone()
    {
        $phone=input("post.phone");

        if(!$phone || strlen($phone)!=11)
        {
            return c_response('2' , '手机无效！');
        }

        $rs=Db::name("user")->where(["phone"=>$phone])->find();

        if($rs && $rs["uid"]>0)
        {
            return c_response(1 , '手机号已经注册');
        }
        else
        {
            return c_response(0 , '手机号没有注册');
        }
    }

    /**
     * @return \think\response\View
     * 显示界面
     */
    public  function  bindPhone()
    {


        return view("bindphone");
    }

    /**
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     * 注册或绑定账户；
     */
    private function bindPhoneDo($phone,$data)
    {

        $us=Db::name("user")->where(array("phone"=>$phone))->find();

        //var_dump($us);

        try {

            Db::startTrans();
            if($us && $us["uid"]>0)
            {
                //更新微信信息到手机用户中；
                $map=array();
                $map['wx_openid'] = $data['openid'];
                $map['nick_name'] = $data['nickname'];
                $map['oauth'] = $data['oauth'];
                $map['wx_head_pic'] = $data['head_pic'];

                Db::name("user")->where(["uid"=>$us["uid"]])->update($map);
            }
            else
            {
                //注册一个新用户
                $map['phone'] =$phone;
                $map['password'] = md5('123456');
                $map['wx_openid'] = $data['openid'];
                $map['nick_name'] = $data['nickname'];
                $map['create_time'] = time();
                $map['oauth'] = $data['oauth'];
                $map['wx_head_pic'] = $data['head_pic'];
                //$map['sex'] = $data['sex'];
                $map['token'] = md5(time() . mt_rand(1, 99999));

                $pro_code = session("procode");
                $p_user = Db::name("user")->where(array("pro_code" => $pro_code))->find();
                $map['p_uid'] = $p_user["uid"]; // 推荐人id
                $map['ps_uid'] = $p_user["ps_uid"];  //先设置为上级的ps_uid,然后插入数据后再更新；
                Db::name("user")->insert($map);
                $id = Db::getLastInsID();
                if ($id) {
                    //更新ps_uid;
                    Db::execute("update cs_user set ps_uid=concat(ps_uid,',',uid) where uid={$id}");
                }

            }

            Db::commit();
            return 1;
        }
        catch (Exception $ex)
        {
            Db::rollback();
            echo  "exception:".$ex->getMessage();
            // throw $ex;
            return 0;
        }
    }


    public function  WxpaySuccess()
    {
        return view("WxpaySuccess");
    }

    public function  WxpayUnSuccess()
    {
        return view("WxpayUnSuccess");
    }

    public function ali(){

        return view();
    }
    public function wx(){

        return view();
    }

}