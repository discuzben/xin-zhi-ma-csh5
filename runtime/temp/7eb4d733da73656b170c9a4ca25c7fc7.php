<?php /*a:1:{s:64:"/cs/docker/nginx/html/csh5/application/mobile/view/index/me.html";i:1546484260;}*/ ?>
<style xmlns:v-bind="http://www.w3.org/1999/xhtml" xmlns:v-bind="http://www.w3.org/1999/xhtml">

    .page_me .r1, .page_me .r0
    {
        background: #1b99dc;
    }
    .page_me .r0 *
    {
        color: #ffffff;
    }

    .page_me .r0 div:first-child
    {
        text-align:left;
        padding-left: 2%;
        font-size: 12px;
    }
    .page_me .r0 div:first-child i
    {
        margin-right: 3px;
    }
    .page_me .r0 div:nth-child(2)
    {
        text-align: center;
        font-size: 16px;
    }
    .page_me .r0 div:last-child
    {
        text-align:right;
        padding-right: 2%;
        font-size: 12px;
    }
    .page_me .r0 div:last-child i
    {
        margin-right: 3px;
    }

    .page_me  .r1 .weui-flex__item
    {
        margin: 5px 0 5px 0px;
    }
    .page_me .r1 .weui-flex__item i,  .r1 .weui-flex__item span
    {
        width: 100%;
        display: block;
        text-align: center;
        color: #ffffff;

    }
    .page_me .r1 .weui-flex__item i
    {
        font-size: 39px;
    }

    .page_me .weui-grid
    {
        width: 25%;
    }

    .page_me .r2 .weui-grid__icon
    {
        width: 39px;
        height: 39px;
    }
    .page_me .r2 .icon
    {
        width: 39px;
        height: 39px;
        border-radius: 100%;
        color: #ffffff;
        line-height: 39px;
        text-align: center;

    }

    .page_me .r2 .icon1 {
        background: #3792fc;

    }
    .page_me  .r2 .icon2 {
        background: #8f2dfc;

    }

    .page_me .r2 .icon3 {
        background: #e7136a;
        font-size: 18px;

    }
    .page_me .r2 .icon4 {

        background: #26e9b4;
    }

    .page_me .r1  div_user_logo
    {
        width: 25%;
    }
    .page_me .r1 .user_logo
    {
        width: 50px;
        height: 50px;
        border-radius: 100%;

        margin: 20px ;

    }
    .page_me .r1 .user_info
    {
        color: #ffffff;
        font-size: 16px;
        margin-top: 20px;
        width: 70%;

    }

    .page_me .r1 .user_info p span:first-child
    {
        color: orange;
        font-size: 15px;
        float: left;
    }

    .page_me .r1 .user_info p span:last-child
    {
        color: orange;
        font-size: 14px;
        float: right;
    }

    .page_me .r3  .weui-cell__hd  i
    {
        font-size: 20px;
        color: #1b99dc;
        margin-right: 5px;
    }

</style>
<div class="page_me">
    <div class="weui-flex r0">
        <div class="weui-flex__item"><i class="font_family icon-bangzhuzhongxin"></i><span>帮助中心</span></div>
        <div class="weui-flex__item">会员中心</div>
        <div class="weui-flex__item"><i class="font_family icon-shezhi"></i><span>设置</span></div>
    </div>
    <div class="weui-flex r1" id="page_me_userinfo">
        <div class="div_user_logo">
            <img  class="user_logo" :src="pic_head">
        </div>

        <div class="user_info">
            昵称：{{username}}
            <p><span class="member_level">级别：{{role}}</span><span class="member_phone">Mob:{{phone}}</span></p>
        </div>
    </div >

    <div class="weui-grids r2">
        <a  class="weui-grid js_grid" name="me_znhk">
            <div class="weui-grid__icon">
                <i class="font_family icon-zhinenghuankuanicon icon icon1"></i>
            </div>
            <p class="weui-grid__label">
                智能还款
            </p>
        </a>
        <a  class="weui-grid js_grid" name="me_mpos">
            <div class="weui-grid__icon">
                <i class="font_family icon-POSji icon icon2"></i>
            </div>
            <p class="weui-grid__label">
                MPOS
            </p>
        </a>
        <a  class="weui-grid js_grid" name="me_hysj">
            <div class="weui-grid__icon">
                <i class="font_family icon-huiyuanshengji icon icon3"></i>
            </div>
            <p class="weui-grid__label">
                会员升级
            </p>
        </a>
        <a  class="weui-grid js_grid" name="me_jsjk">
            <div class="weui-grid__icon">
                <i class="font_family icon-jisudaikuan icon icon4"></i>
            </div>
            <p class="weui-grid__label">
                极速借款
            </p>
        </a>

    </div>

    <div class="weui-cells r3">
        <div class="weui-cell weui-cell_access" name="add_credit_card">
            <div class="weui-cell__hd"><i class="font_family icon-tianjiaxinyongqia"></i></div>
            <div class="weui-cell__bd">
                <p>添加信用卡</p>
            </div>
            <div class="weui-cell__ft"></div>
        </div>
        <div class="weui-cell weui-cell_access" name="add_bank_card">
            <div class="weui-cell__hd"><i class="font_family icon-tianjiachuxuqia"></i></div>
            <div class="weui-cell__bd">
                <p>添加储蓄卡</p>
            </div>
            <div class="weui-cell__ft"></div>
        </div>
        <div class="weui-cell weui-cell_access" name="mycard">
            <div class="weui-cell__hd"><i class="font_family icon-womenyinhangqia"></i></div>
            <div class="weui-cell__bd">
                <p>我的银行卡</p>
            </div>
            <div class="weui-cell__ft"></div>
        </div>
        <div class="weui-cell weui-cell_access">
            <div class="weui-cell__hd"><i class="font_family icon-shoukuanjilu"></i></div>
            <div class="weui-cell__bd">
                <p>刷卡收款记录</p>
            </div>
            <div class="weui-cell__ft"></div>
        </div>
        <div class="weui-cell weui-cell_access" name="my_policy">
            <div class="weui-cell__hd"><i class="font_family icon-wodebaodan"></i></div>
            <div class="weui-cell__bd">
                <p>我的保单</p>
            </div>
            <div class="weui-cell__ft"></div>
        </div>
        <div class="weui-cell weui-cell_access" name="real_name">
            <div class="weui-cell__hd"><i class="font_family icon-anquanshezhi"></i></div>
            <div class="weui-cell__bd">
                <p>实名认证</p>
            </div>
            <div class="weui-cell__ft"></div>
        </div>
</div>

<script>
    var vm_me = new Vue({
        el: '#page_me_userinfo',
        data: {
            username: '<?php echo htmlentities($user['nick_name']); ?>',
            phone: '<?php echo htmlentities($user['phone']); ?>',
            role: "<?php echo htmlentities($user['role']['role_name']); ?>",
            pic_head:'<?php echo htmlentities($user['wx_head_pic']); ?>'
        },
        methods: {}
    })


    //
    $('.page_me .r2 a').on("click",function () {

        $.showLoading();

        var btn_name = $(this).attr("name");
        switch (btn_name) {


            case "me_znhk":
                //还款管理
                var winname = "win_zhinenghuankuan";
                var wintitle = "智能还款";
                var winurl = "/mobile/repayment/repaymentManagerView";
                var windata = {};
                CreateKeeWindow.openWindow(winname, wintitle, winurl, windata)
                break;
            case "me_mpos":
                $.alert("功能建设中...","手刷POS机");
                $.hideLoading();
                break;


            case "me_hysj":
                //会员升级
                var winname = "win_huiyuanshengji";
                var wintitle = "产品升级";
                var winurl = "/mobile/user/upgradeView";
                var windata = {};
                CreateKeeWindow.openWindow(winname, wintitle, winurl, windata)
                break;
            case "me_jsjk":

                //极速借款
                var winname = "win_jsdk";
                var wintitle = "极速贷款";
                var winurl = "/mobile/home/daiKuang";
                var windata = {};
                CreateKeeWindow.openWindow(winname, wintitle, winurl, windata)
                break;
            default:
                $.hideLoading();
                break;
        }
    })

    //
    $('.page_me .r3 .weui-cell').on("click",function () {

        $.showLoading();

        var btn_name = $(this).attr("name");
        switch (btn_name) {
            case "add_credit_card":
                var winname="win_addcreditcard";
                var wintitle="添加信用卡";
                var winurl="/mobile/bankCard/addCreditCardView";
                var windata={};
                CreateKeeWindow.openWindow(winname,wintitle,winurl,windata)
                break;

            case "add_bank_card":
                var winname="win_addbankcard";
                var wintitle="添加银行卡";
                var winurl="/mobile/bankCard/addDebitCardView";
                var windata={};
                CreateKeeWindow.openWindow(winname,wintitle,winurl,windata)
                break;

            case "mycard":
                var winname="win_mycard";
                var wintitle="我的银行卡";
                var winurl="/mobile/BankCard/myCardView";
                var windata={};
                CreateKeeWindow.openWindow(winname,wintitle,winurl,windata)
                break;

            case "my_policy":
                var winname="win_mypolicy";
                var wintitle="我的保单";
                var winurl="/mobile/user/myPolicyView";
                var windata={};
                CreateKeeWindow.openWindow(winname,wintitle,winurl,windata)
                break;

            case "real_name":
                var winname="win_real_name";
                var wintitle="实名认证";
                var winurl="/mobile/user/realNameView";
                var windata={};
                CreateKeeWindow.openWindow(winname,wintitle,winurl,windata)
                break;

            case "register":
                var winname="win_register";
                var wintitle="注册";
                var winurl="/mobile/login/registerView";
                var windata={};
                CreateKeeWindow.openWindow(winname,wintitle,winurl,windata)
                break;

            default:
                $.hideLoading();
                break;
        }

    })

</script>