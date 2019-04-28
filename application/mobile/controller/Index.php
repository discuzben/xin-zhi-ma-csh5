<?php
namespace app\mobile\controller;

class Index extends WebBase
{
    public function index()
    {
       // $route=input('get.route');
       // $this->assign("route",$route);
        //var_dump(session("user"));
         return view("index");
    }

    public function home()
    {
        return view("home");
    }

    public function info()
    {
        return view("info");
    }

    public function infoDetailView()
    {
        return view("infoDetail");
    }

    public function share()
    {
        return view("share");
    }

    public function me()
    {
        $this->assign("user",session("user"));
        return view("me");
    }




    public  function  test()
    {
        echo  date('Y-m-d H:i:s',time());
        die;
        return view();
    }


    public  function  wxtest()
    {
        $redirect_uri = urlencode("http://h5t.xinzhima.cn/index.php/mobile/index/getcode");
        $appid = "wx11a3b0bdb8cd5dff";

        $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appid}&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";

        //echo $url;
       // die;

        header("location:".$url);



    }

    public  function getcode()
    {
        echo "code:".input('get.code');
    }


    public function  test1()
    {
        $user = \app\mobile\model\User::find(361240796);

        $role=$user->role;


    }



}
