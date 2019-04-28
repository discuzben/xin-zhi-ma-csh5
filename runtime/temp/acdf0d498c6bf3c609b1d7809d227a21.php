<?php /*a:1:{s:79:"/cs/docker/nginx/html/csh5/application/mobile/view/bank_card/addCreditCard.html";i:1545898188;}*/ ?>

<div class="page_addcreditcard" id="page_addcreditcard">
    <div class="weui-cells" >
        <div class="weui-cell">
            <div class="weui-cell__hd">
                <label class="weui-label">持卡人：</label>
            </div>
            <div class="weui-cell__bd">
                <input  class="weui-input" type="text" placeholder="请输入您的姓名"  readonly  v-model="username" >
            </div>
        </div>

        <div class="weui-cell">
            <div class="weui-cell__hd">
                <label class="weui-label">银行卡号：</label>
            </div>
            <div class="weui-cell__bd">
                <input  class="weui-input" type="text" placeholder="请输入您的信用卡卡号"v-model="card_no">
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

        <div class="weui-cell">
            <div class="weui-cell__hd">
                <label class="weui-label">安全码：</label>
            </div>
            <div class="weui-cell__bd">
                <input  class="weui-input" type="text" placeholder="信用卡背面CVN2后三位数字"  v-model="security_code">
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd">
                <label class="weui-label">卡有效期：</label>
            </div>
            <div class="weui-cell__bd">
                <input  class="weui-input" type="text" placeholder="信用卡有效期,如:09/22"  v-model="expired_time">
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd">
                <label class="weui-label">账单日期：</label>
            </div>
            <div class="weui-cell__bd">
                <input  class="weui-input" type="text" placeholder="出账单日期,如:25"  v-model="bill_day">
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd">
                <label class="weui-label">还款日期：</label>
            </div>
            <div class="weui-cell__bd">
                <input  class="weui-input" type="text" placeholder="最后还款日期,如:12"  v-model="repayment_day">
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd">
                <label class="weui-label">信用额度：</label>
            </div>
            <div class="weui-cell__bd">
                <input  class="weui-input" type="text" placeholder="信用卡信用额度,如:20000"  v-model="credit_blance">
            </div>
        </div>

    </div>
    <button type="button"  class="weui-btn weui-btn_primary btn_credit_submit">提 交</button>
</div>

<script>
   var real_name='<?php echo htmlentities($user['real_name']); ?>';

   // alert(real_name);

    var pageaddcc_vm1 = new Vue({
        el: '#page_addcreditcard',
        data: {
            username:real_name,
            card_no: null,
            phone: null,
            security_code: null,
            expired_time: null,
            cardtype: 1,
            bill_day:null,
            repayment_day:null,
            credit_blance:null,
            token:'<?php echo htmlentities($user['token']); ?>',

        },
        methods: {
            checkData: function(e) {
            }
        }
    })

    $(function () {

        $('.page_addcreditcard .btn_credit_submit').on("click",function () {



            var data = JSON.parse(JSON.stringify(pageaddcc_vm1.$data)); //复制数据; 因为默认传的是引用;
            delete data.username;

            console.log(JSON.stringify(data));

            //验证数据
            if(!data.card_no || data.card_no == "") {
                $.alert("请填写卡号！");

                return false;
            }
            if(!data.phone || data.phone == "") {
                $.alert("请填写预留手机号！");
                return false;
            }
            if(!data.security_code || data.security_code == "") {
                $.alert("请填写安全码！");
                return false;
            }
            if(!data.expired_time || data.expired_time == "") {
                $.alert("请填写有效期！");
                return false;
            }


            $.showLoading();
            $.post("http://pay2.xinzhima.cn/index/leshuapay/AddCard",data,function (data,status) {

                console.log(JSON.stringify(data));
                $.hideLoading();
                if(data.code==1)
                {
                    $.toast("添加成功！");

                    //关闭本页面；

                    //打开我的银行卡页面；

                }
                else
                {
                    $.alert(data.msg);
                }

            })


        })
    })

</script>