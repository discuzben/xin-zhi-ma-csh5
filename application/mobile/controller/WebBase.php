<?php
/**
 * Created by PhpStorm.
 * User: leo
 * Date: 2018/12/18
 * Time: 16:03
 */

namespace app\mobile\controller;


use think\App;
use think\Controller;
use think\Db;
use app\mobile\utils;

class WebBase extends Controller
{
    protected $expire=3600;
    protected  $prename="cs_";
    public $weixin_config;

    function  __construct(App $app = null)
    {
        parent::__construct($app);


        //获取微信配置
        $wechat_list = Db::name('wx_user')->select();
        // echo "<pre>";        var_dump($wechat_list);        die;

        //推广码；
        if(input('get.procode'))
        {
            session("procode",input("get.procode"));
        }

        //微信浏览器
        if(strstr($_SERVER['HTTP_USER_AGENT'],'MicroMessenger') && $wechat_list[0]['wait_access']) {

            $wechat_config = $wechat_list[0];
            $this->weixin_config = $wechat_config;
            $this->assign('wechat_config', $wechat_config); // 微信配置

            if ($wechat_config && !session('openid')) {
                //去授权获取openid
                $wxuser = $this->GetOpenid();

                //echo "<pre>";
              //  var_dump($wxuser);die;

                //获取用户昵称
                session('subscribe', $wxuser['subscribe']);// 当前这个用户是否关注了微信公众号
                //微信自动登录
                $data = array(
                    'openid' => $wxuser['openid'],
                    'oauth' => 'weixin',
                    'nickname' => trim($wxuser['nickname']) ? trim($wxuser['nickname']) : '微信用户',
                    'sex' => $wxuser['sex'],
                    'head_pic' => $wxuser['headimgurl'],
                );

                //处理用户登录
                $logic = new \app\mobile\utils\UsersLogic();
                $login_result = $logic->thirdLogin($data);


                if ($login_result['status'] == 1) {

                    //登录成功，直入session
                    session("user",$login_result['result']);
                }
                else
                {
                    echo "登录失败";
                }

            }
            else
            {
                //session("openid")存在，就直接登录；
                $userdata=get_user_info(session("openid"),3,"weixin");

              //  echo "<pre>";
               // var_dump($userdata);

               if(!$userdata)
               {
                   session('openid',null);
                   echo "登录失败，请重试！";
                   die;
               }
               else
               {
                   session("user",$userdata);
               }

            }

            // 微信Jssdk 操作类 用分享朋友圈 JS
           // $jssdk = new \app\mobile\utils\Jssdk($this->weixin_config['appid'], $this->weixin_config['appsecret']);
          //  $signPackage = $jssdk->GetSignPackage();
           // $this->assign('signPackage', $signPackage);
        }
        else
        {
            // exit("请在微信中打开，谢谢！");
        }

    }






    /**
     * begin 微信相关的函数
     */

    // 网页授权登录获取 OpendId
    public function GetOpenid()
    {
        if(session('openid'))
        {
            return session("openid");
        }

        //通过code获得openid
        if (!isset($_GET['code'])){
            //触发微信返回code码
            //$baseUrl = urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);

            //回调地址，本网站地址；
            $baseUrl = urlencode($this->get_url());

            //生成获取code的url地址
            $url = $this->__CreateOauthUrlForCode($baseUrl);

            // 跳转到微信授权页面 需要用户确认登录的页面
            Header("Location: $url");
            exit();
        } else {
            // 上面跳转, 这里跳了回来
            //获取code码，以获取openid
            $code = $_GET['code'];

            $data = $this->getOpenidFromMp($code);
//            echo "<pre>";
//            var_dump($data);

            $data2 = $this->GetUserInfo($data['access_token'],$data['openid']);
//            echo"<br>data2<br>";
//            var_dump($data2);
//            die;

            $data['nickname'] = $data2['nickname'];
            $data['sex'] = $data2['sex'];
            $data['headimgurl'] = $data2['headimgurl'];
            $data['subscribe'] = $data2['subscribe'];
            session("openid",$data["openid"]);
            return $data;

        }
    }

    /**
     * 获取当前的url 地址
     * @return type
     */
    private function get_url() {
        $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
        $php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
        $path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
        $relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : $path_info);
        return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url;
    }

    /**
     *
     * 通过code从工作平台获取openid机器access_token
     * @param string $code 微信跳转回来带上的code
     *
     * @return openid
     */
    public function GetOpenidFromMp($code)
    {
        //通过code换取网页授权access_token  和 openid
        $url = $this->__CreateOauthUrlForOpenid($code);
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);//设置超时
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $res = curl_exec($ch);//运行curl，结果以jason形式返回
        $data = json_decode($res,true);//取出openid access_token
        curl_close($ch);

        return $data;
    }


    /**
     *
     * 通过access_token openid 从工作平台获取UserInfo
     * @return openid
     */
    public function GetUserInfo($access_token,$openid)
    {
        // 获取用户 信息
        $url = $this->__CreateOauthUrlForUserinfo($access_token,$openid);
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);//设置超时
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $res = curl_exec($ch);//运行curl，结果以jason形式返回
        $data = json_decode($res,true);//取出openid access_token
        curl_close($ch);

        // 获取看看用户是否关注了 你的微信公众号， 再来判断是否提示用户 关注
        $access_token2 = $this->get_access_token();
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token2&openid=$openid";
        $subscribe_info = httpRequest($url,'GET');
        $subscribe_info = json_decode($subscribe_info,true);
        $data['subscribe'] = $subscribe_info['subscribe'];

        return $data;
    }


    public function get_access_token(){
        //判断是否过了缓存期
        $wechat = Db::name('wx_user')->find();
        $expire_time = $wechat['web_expires'];

        if($expire_time > time()){
            return $wechat['web_access_token'];
        }

        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$wechat['appid']}&secret={$wechat['appsecret']}";
        $return = httpRequest($url,'GET');
        $return = json_decode($return,1);
        $web_expires = time() + 7000; // 提前200秒过期
        Db::name('wx_user')->where(array('id'=>$wechat['id']))->update(array('web_access_token'=>$return['access_token'],'web_expires'=>$web_expires));
        return $return['access_token'];
    }

    /**
     *
     * 构造获取code的url连接
     * @param string $redirectUrl 微信服务器回跳的url，需要url编码
     *
     * @return 返回构造好的url
     */
    private function __CreateOauthUrlForCode($redirectUrl)
    {
        $urlObj["appid"] = $this->weixin_config['appid'];
        $urlObj["redirect_uri"] = "$redirectUrl";
        $urlObj["response_type"] = "code";
//        $urlObj["scope"] = "snsapi_base";
        $urlObj["scope"] = "snsapi_userinfo";
        $urlObj["state"] = "614"."#wechat_redirect";
        $bizString = $this->ToUrlParams($urlObj);
        return "https://open.weixin.qq.com/connect/oauth2/authorize?".$bizString;
    }

    /**
     *
     * 构造获取open和access_toke的url地址
     * @param string $code，微信跳转带回的code
     *
     * @return 请求的url
     */
    private function __CreateOauthUrlForOpenid($code)
    {
        $urlObj["appid"] = $this->weixin_config['appid'];
        $urlObj["secret"] = $this->weixin_config['appsecret'];
        $urlObj["code"] = $code;
        $urlObj["grant_type"] = "authorization_code";
        $bizString = $this->ToUrlParams($urlObj);
        return "https://api.weixin.qq.com/sns/oauth2/access_token?".$bizString;
    }

    /**
     *
     * 构造获取拉取用户信息(需scope为 snsapi_userinfo)的url地址
     * @return 请求的url
     */
    private function __CreateOauthUrlForUserinfo($access_token,$openid)
    {
        $urlObj["access_token"] = $access_token;
        $urlObj["openid"] = $openid;
        $urlObj["lang"] = 'zh_CN';
        $bizString = $this->ToUrlParams($urlObj);
        return "https://api.weixin.qq.com/sns/userinfo?".$bizString;
    }

    /**
     *
     * 拼接签名字符串
     * @param array $urlObj
     *
     * @return 返回已经拼接好的字符串
     */
    private function ToUrlParams($urlObj)
    {
        $buff = "";
        foreach ($urlObj as $k => $v)
        {
            if($k != "sign"){
                $buff .= $k . "=" . $v . "&";
            }
        }

        $buff = trim($buff, "&");
        return $buff;
    }

    /** end  微信相关的函数  */
}