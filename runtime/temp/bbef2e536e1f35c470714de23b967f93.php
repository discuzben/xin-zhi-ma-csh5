<?php /*a:2:{s:67:"/cs/docker/nginx/html/csh5/application/mobile/view/index/index.html";i:1546502154;s:35:"./static/cs-ui/files/keewindow.html";i:1545374717;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>芯芝麻APP</title>
    <link rel="stylesheet" href="/static/mobile/css/index.css">
    <link rel="stylesheet" href="/static/mobile/css/account_progress_default.css">
    <link rel="stylesheet" href="/static/mobile/css/account_progress_styles.css">
    <link rel="stylesheet" href="/static/awesome/css/font-awesome.css">
    <link rel="stylesheet" href="/static/jweui/lib/weui.min.css">
    <link rel="stylesheet" href="/static/jweui/css/jquery-weui.css">
    <link rel="stylesheet" href="/static/mobile/iconfont/iconfont.css">

    <script src="/static/mobile/js/jquery-3.3.1.min.js"></script>
</head>
<body>
<div id="index_contents">

</div>
<div class="footer">
    <a class="btn_home"><i class="font_family icon-shouye"></i><span>首页</span></a>
    <a class="btn_info"><i class="font_family icon-zixun"></i><span>资讯</span></a>
    <a class="btn_share"><i class="font_family icon-fenxiang" ></i><span>分享</span></a>
    <a class="btn_me"><i class="font_family icon-wode"></i><span>我的</span></a>
</div>

<div style="display: none" id="newkeewindow">
    <style>
        #keewindow {
            display: none;
            position: fixed;
            background: #FFFFFF;
            width: 100%;
            height: 100%;
            left: 0;
            top: 0;

        }

        #keewindow .window_head {
            position: fixed;
            z-index: 200;
           /* background: #FFFFFF;*/
            background:#1aad19;
            width: 100%;
            height: 50px;
            line-height: 50px;
            text-align: center;
            font-size: 20px;
            color: #FFFFFF;
          /*  background: rgb(253, 252, 250);*/
            box-shadow: 1px 1px 1px lightgray;

        }

        #keewindow .window_head span {
            position: relative;
            font-size: 20px;
            line-height: 50px;
            width: 70%;
            text-align: center;
            display: block;
            float: left;
            color: #ffffff;
        }

        #keewindow .window_head i {
            float: left;
            font-size: 30px;
            line-height: 50px;
            width: 15%;

            color: #ffffff;
           /* color: rgb(144, 124, 115);*/
        }

        #keewindow .keewindow_contents {
            position: relative;
            width: 100%;
            display: inline-block;
            background: rgb(239, 234, 231);
            overflow-y: scroll;
            margin-top: 50px;

        }

        #keewindow .keewindow_contents table thead {
            height: 39px;
            width: 100%;
            background: #1aad19;/* rgb(253, 252, 250);*/
        }

        #keewindow .keewindow_contents table thead th {
            text-align: center;
            height: 39px;
            line-height: 39px;
            color: #ffffff;
            font-size: 12px;
            vertical-align: middle;
        }

        #keewindow .keewindow_contents table tr {
            height: 26px;
            background: rgb(253, 252, 250);
            border-bottom: 1px solid rgb(239, 234, 231);
        }

        #keewindow .keewindow_contents table tr td {
            text-align: center;
            height: 26px;
            line-height: 26px;
        }

        #keewindow .keewindow_contents .row {
            width: 96%;
            margin-left: 2%;
            border-radius: 3px;
            min-height: 40px;
            background: rgb(253, 252, 250);
            margin-top: 3px;
            padding: 0;
            padding-left: 5px;
            display: block;
            float: left;
            height: auto;
        }

    </style>
    <div data-role="none" id="keewindow">
        <div class="window_head" data-role="none">
            <a data-role="none" href="#" class="btn_close css_close"><i class="fa fa-angle-left"></i></a>
            <span id="keewindow_title">Title</span><i class="fa fa-search" style=" font-size: 26px; display: none"></i>
        </div>
        <div data-role="none" class="keewindow_contents" id="keewindow_contents">
        </div>
    </div>

    <script>
        $(function () {
            var screenheight = document.documentElement.clientHeight;
            $('#keewindow .keewindow_contents').height(screenheight - 50);
        })
    </script>
</div>


</body>
<script src="/static/cs-ui/js/common.js"></script>
<script src="/static/jweui/js/jquery-weui.js"></script>
<script src="/static/mobile/js/vue.min.js"></script>
<script src="/static/mobile/js/fastclick.js"></script>
<script src="/static/mobile/js/global.js"></script>

</html>
<script>
    $(function() {
        //#index_contents的高度
        // $.showLoading();
        var screenheight = document.documentElement.clientHeight;
        $('#index_contents').height(screenheight - 60);

        //判断需要的弹出框
        // var route='';
        // if(route && route!="")
        // {
        //     //console.log(route);
        //     var winname="win_"+route;
        //     var title="提示信息";
        //     var url="";
        //     CreateKeeWindow.createKeeWindow("keewindow",winname);
        //     switch (route) {
        //         case "":
        //             break;
        //
        //         default:
        //             break;
        //     }
        //
        //     CreateKeeWindow.setTitle(winname,title);
        //     $("#"+winname+"_contents").load(url,{},function () {
        //         $.hideLoading();
        //     })
        //     CreateKeeWindow.showKeeWindow(winname);
        //
        // }



        //footer事件；

        $('#index_contents').load("<?php echo url('mobile/index/home'); ?>", {}, function (data, status) {
            $.hideLoading();
        })

        $('.footer .btn_home').on("click", function () {
            $.showLoading();
            $('#index_contents').load("<?php echo url('mobile/index/home'); ?>", {}, function (data, status) {
                $.hideLoading();
            })
        })

        $('.footer .btn_info').on("click", function () {
            $.showLoading();
            $('#index_contents').load("<?php echo url('mobile/index/info'); ?>", {}, function (data, status) {
                $.hideLoading();
            })
        })
        $('.footer .btn_share').on("click", function () {
            $.showLoading();
            $('#index_contents').load("<?php echo url('mobile/index/share'); ?>", {}, function (data, status) {
                $.hideLoading();
            })
        })

        $('.footer .btn_me').on("click", function () {
            $.showLoading();
            $('#index_contents').load("<?php echo url('mobile/index/me'); ?>", {}, function (data, status) {
                $.hideLoading()
            })
        })
    })
</script>

