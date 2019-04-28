<?php /*a:1:{s:72:"/cs/docker/nginx/html/csh5/application/mobile/view/bank_card/myCard.html";i:1546948000;}*/ ?>
<style>

    .page_mycard .mui-card:first-child
    {
        margin-top: 5px;
    }

    .page_mycard .mui-card
    {
        display: block;
        float: left;
        width: 96%;
        margin-left: 2%;
        background: #ffffff;
        border-radius: 5px;
        margin-bottom: 5px;

        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.2), 0 2px 5px 0 rgba(0, 0, 0, 0.19);
    }

    .page_mycard .mui-card  .bank_logo
    {
        width: 40px ;
        height: 40px;
        margin: 5px 0 0 5px;
    }

    .page_mycard .mui-card .mui-card-header span
    {
        float: right;
        height: 40px;
        line-height: 40px;
        margin: 5px 2% 0 0 ;
    }

    .page_mycard .mui-card-content .card-c-left
    {
        width: 60%;
        display: block;
        float: left;
    }
    .page_mycard .mui-card-content .card-c-left span
    {
        float: left;
        width: 100%;
        display: block;
        height: 30px;
        line-height: 30px;
        padding-left: 2%;
    }

    .page_mycard .mui-card-content .btn-updateinfo,.page_mycard .btn-deletecard
    {
        float: right;
        margin: 15px 2% 0 0;
    }



</style>

<div class="page_mycard">
    <div class="weui-tab">
        <div class="weui-navbar">
            <a class="weui-navbar__item weui-bar__item--on" href="#item1" id="btn_load_credit_card">信用卡</a>
            <a class="weui-navbar__item" href="#item2" id="btn_load_bank_card">储蓄卡</a>
        </div>

        <div class="weui-tab__bd">
            <div id="item1" class="weui-tab__bd-item weui-tab__bd-item--active" >
                    <div class="mui-card " v-for="card in cards ">
                        <!--页眉，放置标题-->
                        <div class="mui-card-header">
                            <img class="bank_logo" :src="'/static/imgs/credit/'+card.bank.logo" />
                            <span>{{card.bank_name}}</span>

                        </div>
                        <!--内容区-->

                        <div class="mui-card-content">
                            <div class="card-c-left">
                                <span class="mui-table-view">
                                {{card.user_name}}
                            </span>
                                <span class="mui-table-view">{{card.card_no}}</span>
                            </div>

                            <button class="weui-btn weui-btn_mini weui-btn_primary  btn-updateinfo" type="button" @click="openChangeCardInfo(card)">修改信息</button>
                        </div>

                    </div>
            </div>

            <div id="item2" class="weui-tab__bd-item">


                    <div class="mui-card" v-for="card in cards ">
                        <!--页眉，放置标题-->
                        <div class="mui-card-header">
                            <img class="bank_logo" :src="'/static/imgs/credit/'+card.bank.logo" />
                            <span>{{card.bank_name}}</span>
                        </div>
                        <!--内容区-->
                        <div class="mui-card-content">
                            <div class="card-c-left">
                            <span class="mui-table-view">{{card.user_name}}</span>
                            <span class="mui-table-view">{{card.card_no}}</span>
                            </div>
                            <button type="button"  class="weui-btn weui-btn_mini weui-btn_primary  btn-deletecard" @tap="unbindDebitCard(card)">解 绑</button>
                        </div>


                    </div>



            </div>
        </div>

    </div>

</div>


<script>

    function load_credit_card() {

        var url = "<?php echo url('/mobile/BankCard/creditCards'); ?>";

        $.post(url,
            {},
            function(data) {

                var curdata = data.data;
                console.log(JSON.stringify(curdata));
                mycard_vm1.$data.cards = curdata;


            })
    }

    function load_bank_card() {

        var url = "<?php echo url('/mobile/BankCard/debitCard'); ?>";

        $.post(url,
            {},
            function(data) {
                let curdata = data.data;

                console.log(JSON.stringify(data));
                mycard_vm2.$data.cards = curdata;

                //console.log(JSON.stringify(vm1.$data.cards));
            })
    }

    var mycard_vm1 = new Vue({
        el: '#item1',
        data: {
            cards: []
        },
        methods: {
            openChangeCardInfo(it) {
                page_id = it;

                console.log("asdfsadfas"+page_id);
                var winname = "win_syxq";
                var wintitle = "修改卡信息";
                var winurl = "/mobile/Repayment/repaymentChangeCardInfoView";
                var windata = {};
                CreateKeeWindow.openWindow(winname, wintitle, winurl, windata)
            }
        }

    })

    var mycard_vm2 = new Vue({
        el: '#item2',
        data: {
            cards: []
        },
        methods: {

        }

    })

    $(function () {
        load_credit_card();
        load_bank_card();
    })


</script>