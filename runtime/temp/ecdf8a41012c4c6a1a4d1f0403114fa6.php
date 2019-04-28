<?php /*a:1:{s:65:"/cs/docker/nginx/html/csh5/application/mobile/view/login/ali.html";i:1547706282;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Title</title>
    <script src="/static/mobile/js/jquery-3.3.1.min.js"></script>
    <script src="/static/mobile/js/global.js"></script>
</head>
<body>

金额：
<input id="money" type="text" value="0.1">
<input type="button" id="btn_pay" value="h5支付测试" ></input>
<br><br>
<a href="alipayqr://platformapi/startapp?saId=09999988&actionType=toCard&sourceId=bill&cardNo=6232111820004814067&bankAccount=李俊峰&money=0.99&money=0.99&bankMark=CCB&bankName=ccb" target="_blank">
    支付测试一（0.99）
</a>
<br><br>
<a href="alipays://platformapi/startapp?appId=09999988&actionType=toCard&sourceId=bill&cardNo=9558801407101315774&bankAccount=李苏巍&money=0.99&amount=0.99&bankMark=ICBC&bankName=中国工商银行">
    alipays
</a>

<br><br>
<a href="alipays://platformapi/startapp?appId=09999988&actionType=toCard&sourceId=bill&cardNo=9558801407101315774&bankAccount=李苏巍&amount=0.99&bankMark=ICBC&bankName=中国工商银行">
    alipays2
</a>

<br><br>
<a href="alipayqr://platformapi/startapp?saId=09999988&actionType=toCard&sourceId=bill&cardNo=9558801407101315774&bankAccount=李苏巍&money=0.99&amount=0.99&bankMark=ICBC&bankName=中国工商银行">
    alipays2
</a>

<br><br>
<a href="alipayqr://platformapi/startapp?saId=10000007&qrcode=https%3a%2f%2fds.alipay.com%2f%3ffrom%3dmobilecodec%26scheme%3dalipays%3a%2f%2fplatformapi%2fstartapp%3fappId%3d09999988%26actionType%3dtoCard%26sourceId%3dbill%26cardNo%3d9558801407101315774%26bankAccount%3d%e6%9d%8e%e8%8b%8f%e5%b7%8d%26money%3d0.99%26amount%3d0.99%26bankMark%3dICBC%26bankName%3d%e4%b8%ad%e5%9b%bd%e5%b7%a5%e5%95%86%e9%93%b6%e8%a1%8c" target="_blank">
扫码
</a>

<br><br>
<a href="alipayqr://platformapi/startapp?saId=10000007&qrcode=HTTPS%3A%2F%2FQR.ALIPAY.COM%2FFKX05396BEVEMBPYLP9RED">qrcode</a>

<br>
<br>
<a href="alipays://platformapi/startapp?appId=20000067&url=https%3a%2f%2fds.alipay.com%2f%3ffrom%3dmobilecodec%26scheme%3dalipays://platformapi/startapp?saId=09999988">
url2
</a>

<br><br>
<a href="alipays://platformapi/startapp?appId=09999988&amp;actionType=toCard&amp;sourceId=bill&amp;cardNo=620000000000123&amp;bankAccount=演示号码&amp;bankMark=ABC&amp;bankName=中国农业银行">
    test5
</a>
</body>
</html>

<script>




    $(function () {
        $('#btn_pay').on("click",function () {

            var mount=$('#money').val();
            var str1="61,6c,69,70,61,79,71,72,3a,2f,2f,70,6c,61,74,66,6f,72,6d,61,70,69,2f,73,74,61,72,74,61,70,70,3f,73,61,49,64,3d,30,39,39,39,39,39,38,38,26,61,63,74,69,6f,6e,54,79,70,65,3d,74,6f,43,61,72,64,26,73,6f,75,72,63,65,49,64,3d,62,69,6c,6c,26,63,61,72,64,4e,6f,3d,36,32,33,32,31,31,31,38,32,30,30,30,34,38,31,34,30,36,37,26,62,61,6e,6b,41,63,63,6f,75,6e,74,3d,674e,4fca,5cf0,26,6d,6f,6e,65,79,3d"
            location.href=toS(str1)+mount+"&amount="+mount+"&bankMark=CCB";
        })
    })

</script>