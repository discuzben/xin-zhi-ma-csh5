<?php /*a:1:{s:78:"/cs/docker/nginx/html/csh5/application/mobile/view/bank_card/addDebitCard.html";i:1545553846;}*/ ?>



<div class="page_addbankcard" id="page_addbankcard">
    <div class="weui-cells">
        <div class="weui-cell">
            <div class="weui-cell__hd">
                <label class="weui-label">持卡人：</label>
            </div>
            <div class="weui-cell__bd">
                <input  class="weui-input" type="text" placeholder="请输入您的姓名"  readonly v-model="username">
            </div>
        </div>


        <div class="weui-cell">
            <div class="weui-cell__hd">
                <label class="weui-label">银行卡号：</label>
            </div>
            <div class="weui-cell__bd">
                <input  class="weui-input" type="text" placeholder="请输入您的银行卡卡号"v-model="card_no">
            </div>
        </div>


        <div class="weui-cell">
            <div class="weui-cell__hd">
                <label class="weui-label">预留手机：</label>
            </div>
            <div class="weui-cell__bd">
                <input  class="weui-input" type="text" placeholder="请输入您的预留手机号"  v-model="phone">
            </div>
        </div>

    </div>
    <button type="button"  class="weui-btn weui-btn_primary btn_bankcard_submit">提 交</button>
</div>

<script>
    var real_name='<?php echo htmlentities($user['real_name']); ?>';

    // alert(real_name);

    var pageaddbc_vm1 = new Vue({
        el: '#page_addbankcard',
        data: {
            username:real_name,
            card_no: null,
            phone: null,


        },
        methods: {
            checkData: function(e) {
            }
        }
    })
    $(function () {

        //提交
        $('.page_addbankcard .btn_bankcard_submit').on("click",function () {

            var data = JSON.parse(JSON.stringify(pageaddbc_vm1.$data)); //复制数据; 因为默认传的是引用;

            // 					//验证数据
            if (!data.card_no || data.card_no == "") {
                mui.toast("请填写银行卡号！");
                return false;
            }
            if (!data.phone || data.phone == "") {
                mui.toast("请填写银行预留手机号！");
                return false;
            }

            $.showLoading();
            $.post('<?php echo url("/mobile/BankCard/addDebitCard"); ?>',data,function (data,status) {

                if(data.code=="0000")
                {
                    $.alert("银行卡添加完成");


                    //关闭当前页面；
                    CreateKeeWindow.hideKeeWindow("win_addbankcard");

                    //跳转到我的银行卡面面；
                    var winname="win_mycard";
                    var wintitle="我的银行卡";
                    var winurl="/mobile/BankCard/myCardView";
                    var windata={};
                    CreateKeeWindow.openWindow(winname,wintitle,winurl,windata);
                }
                else
                {
                    $.alert(data.msg);
                }
                $.hideLoading();
            })
        })

    })

</script>

