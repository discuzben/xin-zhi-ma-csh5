<?php /*a:1:{s:71:"/cs/docker/nginx/html/csh5/application/mobile/view/index/bindphone.html";i:1545713109;}*/ ?>

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

    <style>
        .page_bindphone .btn_bindphone
        {
            margin-top: 30px;
            width: 95%;
            margin-left: 2.5%;
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
                <input class="weui-input" type="tel" placeholder="请输入手机号">
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
                <input class="weui-input" type="tel" placeholder="输入验证码">
            </div>
            <div class="weui-cell__ft">
            </div>
        </div>
    </div>
    <a href="javascript:;" class="weui-btn weui-btn_primary btn_bindphone">绑 定</a>
</div>

</body>
</html>
<script>
    $(function () {

        $('.weui-vcode-btn').on("click",function () {

            //倒计时

            //发送验证码

        })


        //提交注册用户；
        $('.btn_bindphone').on("click",function () {




        })


    })
</script>

