<?php

namespace app\mobile\utils;


use app\mobile\controller\Login;
use app\mobile\model\User;
use think\Db;
use think\Exception;

/**
 * 分类逻辑定义
 */
class UsersLogic
{

    /*
     * 微信登录
     * 1.用户不存在，输入手机号，新建一个用户； 如果手机号存在用户，就绑定到原手机号用户；
     * 2.用户存在，直接登录；
     */
    public function thirdLogin($data = array())
    {


        $openid = $data['openid']; //第三方返回唯一标识
        $oauth = $data['oauth']; //来源  "weixin"
        if (!$openid || !$oauth)
            return array('status' => -1, 'msg' => '参数有误', 'result' => '');

        //使用openid获取用户信息,用来判断用户是否存在；
        $user = get_user_info($openid, 3, $oauth);

        //debug
        //var_dump($user);die;

        if (!$user || $user['uid'] <= 0) {
            //账户不存在，
            //保存微信信息到session, 跳转到输入手机号码页面,注册用户后跳回入口页重新登录；
           // echo "bindphone";

            session("wx_login_info", $data);

            $url = url('/mobile/login/bindphone');
            header("location:" . $url);


        } else {

            //账户已经存在,直接登录；
            $rs = $this->loginByWeixin($data,$user);
            return $rs;
        }


    }

    /**
     * @param $data
     * 使用微信聊到的到信息登录；
     * @return array('status' => 1, 'msg' => '登陆成功', 'result' => $user_data)；
     */
    private function loginByWeixin($data,$user)
    {
        try {
            Db::startTrans();

            //1.判断token是否有效，如无效重新生成一个，更新用户表；
            $thistime = time();
            //token的有效期；
            $expiry_date=Intval($user->token_time)+Intval(config("time.duration")) ;

            //如果token过了有效期，重新生成一个token ,主要是给APP端使用的；
            if ($expiry_date<$thistime) {

                $token = md5(time() . mt_rand(1, 99999));
                $user_update = array();
                $user_update["last_login"] = $thistime;
                $user_update["token"] = $token;
                $user_update["token_time"] = $thistime;

                Db::name("user")->where(array("uid" => $user->uid))->update($user_update);
            }


            //2.保存登录记录到user_session表中；

            $user_session = array();
            $user_session["uid"] = $user->uid;
            $user_session["session_key"] = session_id();
            $user_session["login_time"] = intval($_SERVER['REQUEST_TIME']);
            $user_session["access_time"] = intval($_SERVER['REQUEST_TIME']);
            $user_session["login_ip"] = ip2long($_SERVER['REMOTE_ADDR']);
            $user_session["os"] = "h5";
            $user_session["uuid"] = "web-h5";

            // 登录记录
            Db::name("user_session")->insert($user_session);
            Db::commit();

        } catch (Exception $ex) {
            Db::rollback();
            echo $ex->getMessage();
        }


        $user_data=User::user(["uid"=>$user->uid]);
        return array('status' => 1, 'msg' => '登陆成功', 'result' => $user_data);

    }


}