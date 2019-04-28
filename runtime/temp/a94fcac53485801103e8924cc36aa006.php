<?php /*a:1:{s:71:"/cs/docker/nginx/html/csh5/application/mobile/view/login/bindphone.html";i:1545725322;}*/ ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>芯芝麻APP</title>
    <link rel="stylesheet" href="/static/mobile/css/index.css">
    <link rel="stylesheet" href="/static/awesome/css/font-awesome.css">
    <link rel="stylesheet" href="/static/jweui/lib/weui.min.css">
    <link rel="stylesheet" href="/static/jweui/css/jquery-weui.css">
    <link rel="stylesheet" href="/static/mobile/iconfont/iconfont.css">
    <script src="/static/mobile/js/jquery-3.3.1.min.js"></script>
    <script src="/static/jweui/js/jquery-weui.js"></script>
    <style>
        .page_bindphone .btn_bindphone
        {
            margin-top: 30px;
            width: 95%;
            margin-left: 2.5%;
        }
        input:disabled{
            color: darkgray;
        }

    </style>
</head>
<body>
<div class="page_bindphone">
    <div class="weui-cells__title">绑定手机号：</div>
    <div class="weui-cells">
        <div class="weui-cell">
            <div class="weui-cell__hd">
                <label class="weui-label">手机号</label>
            </div>
            <div class="weui-cell__bd">
                <input id="txt_phone" class="weui-input" type="tel" placeholder="请输入手机号">
            </div>
            <div class="weui-cell__ft">
                <button class="weui-vcode-btn ">获取验证码</button>
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd">
                <label class="weui-label">验证码</label>
            </div>
            <div class="weui-cell__bd">
                <input class="weui-input txt_vcode" type="tel" placeholder="输入验证码">
            </div>
            <div class="weui-cell__ft">
            </div>
        </div>
    </div>
    <a  class="weui-btn weui-btn_primary btn_bindphone">绑 定</a>
</div>

</body>
</html>
<script>
    $(function () {

        function sendCms(phone)
        {
            $.post("/mobile/login/SendSmsCode",{"phone":phone},function (data) {
                if(data.code=="0000")
                {
                    $.toast("验证码已发送！"+data.msg);
                    console.log(data.msg);
                    //倒计时
                    var i=60;
                    var t=setInterval(function () {
                        $('.weui-vcode-btn').text(i+"秒后重发");
                        $('.weui-vcode-btn').attr("disabled","disabled")
                        i--;
                        if(i<=0)
                        {
                            clearInterval(t);
                            $('.weui-vcode-btn').text("获取验证码");
                            $('.weui-vcode-btn').removeAttr("disabled")
                        }
                    },1000)


                }
                else
                {
                    $.alert("验证码发送失账！")
                }
            })
        }

        $('.weui-vcode-btn').on("click",function () {
            //alert("hello");

            //检查手机是否已经注册过了；
            var phone=$('#txt_phone').val();
           // alert(phone)
            $.post("/mobile/login/isExistPhone",{"phone":phone},function (data,status) {

                if(data.code==2)
                {
                    $.alert('手机号无效！')
                }
                else if(data.code==1)
                {
                    $.confirm("手机号已经注册，是否绑定账户？", "提示",
                        function s() {
                            //确认
                           // $.alert("ok")
                            //发送验证码
                            sendCms(phone);


                        }, function c() {
                            //取消
                            $.alert("no")
                            return false;
                        })

                }
                else if(data.code==0)
                {
                    //手机号未注册
                    sendCms(phone);
                }
            })

        })


        //提交注册用户；
        $('.btn_bindphone').on("click",function () {

            $.showLoading();

            var vcode=$('.txt_vcode').val();
            $.post("/mobile/login/registerWeixin", {"vcode":vcode}, function (data, status) {
                if(data.code=="0000")
                {
                    $.toast("注册成功！");
                    location.href="/";
                }
                else
                {
                    $.alert(data.msg);
                    $.hideLoading();
                }

            })
        })


    })
</script>

