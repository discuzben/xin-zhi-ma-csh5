<?php /*a:1:{s:78:"/cs/docker/nginx/html/csh5/application/mobile/view/bankcard/addCreditCard.html";i:1545381557;}*/ ?>


<div class="page_addcreditcard" id="page_addcreditcard">
    <div class="weui-cells" >
        <div class="weui-cell">
            <div class="weui-cell__hd">
                <label class="weui-label">持卡人：</label>
            </div>
            <div class="weui-cell__bd">
                <input  class="weui-input" type="text" placeholder="请输入您的姓名"  v-model="username" value="<?php echo htmlentities($user['real_name']); ?>">
            </div>
        </div>


        <div class="weui-cell">
            <div class="weui-cell__hd">
                <label class="weui-label">银行卡号：</label>
            </div>
            <div class="weui-cell__bd">
                <input  class="weui-input" type="text" placeholder="请输入您的信用卡卡号"v-model="username">
            </div>
        </div>


        <div class="weui-cell">
            <div class="weui-cell__hd">
                <label class="weui-label">预留手机：</label>
            </div>
            <div class="weui-cell__bd">
                <input  class="weui-input" type="text" placeholder="请输入您的预留手机号"  v-model="card_no">
            </div>
        </div>

        <div class="weui-cell">
            <div class="weui-cell__hd">
                <label class="weui-label">安全码：</label>
            </div>
            <div class="weui-cell__bd">
                <input  class="weui-input" type="text" placeholder="信用卡背面CVN2后三位数字"  v-model="phone">
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
  //  var real_name='<?php echo htmlentities($user['real_name']); ?>';

   // alert(real_name);
    /*
    var pageaddcc_vm1 = new Vue({
        el: '#page_addcreditcard',
        data: {
            username:$real_name,
            card_no: null,
            phone: null,
            security_code: null,
            expired_time: null,
            cardtype: 1,
            bill_day:null,
            repayment_day:null,
            credit_blance:null,

        },
        methods: {
            checkData: function(e) {
            }
        }
    })
    */
</script>
