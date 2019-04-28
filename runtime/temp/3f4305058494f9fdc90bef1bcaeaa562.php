<?php /*a:1:{s:77:"/cs/docker/nginx/html/csh5/application/mobile/view/bankcard/addDebitCard.html";i:1545379873;}*/ ?>



<div class="page_addbankcard">
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

    </div>
    <button type="button"  class="weui-btn weui-btn_primary btn_credit_submit">提 交</button>
</div>


