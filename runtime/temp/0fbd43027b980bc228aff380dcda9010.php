<?php /*a:1:{s:74:"/cs/docker/nginx/html/csh5/application/mobile/view/login/WxpaySuccess.html";i:1545888022;}*/ ?>

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
        body{
            text-align: center;
            padding-top:  50px;
        }

    </style>
</head>
<body>

<i class="weui-icon-success" style="font-size: 70px"></i>支付成功！
<p style="margin-top: 30px">正在返回首页(<span id="timer">5</span>)</p>
</body>
</html>
<script>

    $(function () {

        var count=5;
      var t=  setInterval(function () {
          $('#timer').html(count);
          count--;
          if(count<=0)
          {
              clearInterval(t);
              location.href="/";
          }
      },1000);

    })
</script>

