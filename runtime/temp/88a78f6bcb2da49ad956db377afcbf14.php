<?php /*a:1:{s:83:"/cs/docker/nginx/html/csh5/application/mobile/view/repayment/repayment_setPlan.html";i:1546930468;}*/ ?>
<style>
    li{
        list-style: none;
    }

    .page_repayment_set .weui-btn_primary
    {
        width: 96%;
        margin-left: 2%;
    }

    #btn_submit_plan
    {
        margin-bottom: 10px;
    }

    .page_repayment_set .weui-cells__title
    {
        color: red;
    }

    .page_repayment_set .weui-cells{
        margin-top: 5px;
    }

    .weui-btn:after{
        display: none;
    }


    /**   begin日历   ****************************/
    #selfmask {
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        z-index: 998;
        background-color: rgba(0, 0, 0, 0.5);
        display: none;
    }

    #dateview {
        position: fixed;
        z-index: 999;
        bottom: 0px;
        left: 0px;
        width: 100%;
        height: 350px;
        background-color: white;
        display: block;
    }

    #datesel .dateitem span {
        width: 100%;
        color: black;
        font-size: 12px !important;
        display: block;
    }

    #datesel .dateitem span:last-child {
        margin-top: -5px !important;
    }

    #datesel {
        /* background-color: rgba(0,0,0,.3); */
        padding-inline-start: 20px;
    }

    .dateitem {
        margin: 1px;
        /* background-color: whtie; */
        /* border: 1px solid rgba(0,0,0,.3); */
        width: 43px;
        height: 43px;
        border-radius: 50%;
        text-align: center;
        margin: 2px;
        padding: 2px;

        /* line-height: 30px; */
        float: left;
    }


    .bluebackgroud {
        background: #1b99dc;
        color: #ffffff;
    }

    .isSelect {
        background: #1b99dc;
        color: #ffffff;

    }

    .isSelect * {
        color: #ffffff !important;
    }

    /**   end日历      ****************************/

</style>

<div class="page_repayment_set">
    <div class="mui-content" style="position: relative;">
        <div id="div_set" class="weui-cells">
            <div class="title weui-cells__title">温馨提示：单笔最高消息金额不能大于1000元</div>
            <!--ul第一项-->

                <div class="weui-cell" id="btn_pop_area">
                    <div class="weui-cell__hd">
                        <label class="weui-label">城市选择</label>
                    </div>
                    <div  class="weui-cell__bd">
                        <input type="text" id="txt_sel_city" class="weui-input" >
                    </div>

                    <div class="weui-cell__ft" id="sel_city">
                        <i class="fa fa-chevron-down" style=""></i>
                    </div>

                </div>
            <div class="weui-cell" >
                    <div class="weui-cell__hd">
                         <label class="weui-label">还款金额</label>
                    </div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" type="number" placeholder="￥0.00" id="repay_money" v-model="amount">
                    </div>
                <div class="weui-cell__ft">
                    <i class="fa fa-edit" style=""></i>
                </div>


                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"> <label class="weui-label">还款日期</label></div>
                    <div class="div-btn weui-cell__bd">
                        <a class="weui-btn weui-btn_default weui-btn_mini btn-change" id="btn_selectdate_con">连续选择</a>
                        <a class="weui-btn weui-btn_default weui-btn_mini btn-change" id="btn_selectdate_discon">间隔选择</a>
                    </div>
                </div>
                <div class="weui-cell" id="btn_poppicker">
                    <div class="weui-cell__hd">
                        <label class="weui-label">每日笔数</label>
                    </div>
                    <div class="weui-cell__bd">
                        <input type="text" id="txt_count_per" value="2 笔" class="weui-input"  >

                    </div>
                    <div class="weui-cell__ft">
                        <i class="fa fa-chevron-down" style=""></i>
                    </div>
                </div>


            <button type="button" id="btn_credit_submit" class="weui-btn weui-btn_primary set-bill">制定还款计划</button>




            <div class="title weui-cells__title">温馨提示：您的手续费会加在每笔消费中</div>
            <!--ul子项-->

                <div class="weui-cell">
                    <div class="weui-cell__hd">
                        <label class="weui-label">
                            制定时间
                        </label>

                    </div>
                    <div class="weui-cell__bd">
                        <input type="text" class="pay-time weui-input" v-model="thistime">
                    </div>
                </div>

                <div class="weui-cell">
                    <div class="weui-cell__hd">
                        <label class="weui-label">
                        还款总金额
                        </label>
                    </div>
                    <div class="weui-cell__bd">
                        <input type="number" readonly class="pay-all-money weui-input" value="" placeholder="￥0.00" v-model="amount">
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd">
                        <label class="weui-label">
                        还款总笔数
                        </label>
                    </div>
                    <div class="weui-cell__bd">
                        <input type="number" readonly class="pay-all-money weui-input" value="" placeholder="￥0.00" v-model="total_count">
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd">
                        <label class="weui-label">
                        手续费合计
                        </label>
                    </div>
                    <input type="text" readonly v-model="fixamount" class="pay-charge weui-input" value="" placeholder="￥0.00">
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd">
                        <label class="weui-label">
                        需预留额度
                        </label>
                    </div>
                    <div>
                        <input type="text" readonly class="pay-remain weui-input" value="" placeholder="￥0.00" v-model="require_amount">
                    </div>
                </div>

        </div>

        <!--以下是生成的计划数据列表-->
        <div id="div_create_list">

            <div class="weui-cells" v-for="(order,oindex) in replaylist.detail.list">
                <div class="weui-cell">
                    <div class="weui-cell__hd"><i class="font_family icon-wodeyue"></i></div>
                    <div class="weui-cell__bd"><span>还款金额￥{{order.amount}}</span></div>
                    <div class="weui-cell__ft"><span>{{order.timestamp_explain}}</span></div>

                </div>
                <div class="weui-cell trade_row" v-for="(item,index) in order.detail.deduction">

                    <div class="weui-cell__hd">
                        消费(￥{{item.trade_money}})
                    </div>
                    <div class="weui-cell__bd btn_pop_mcc" onclick="pop_picker3(this)" v-bind:index1="oindex" v-bind:index2="index"  >
                        <input type="hidden" v-model="replaylist.detail.list[oindex].detail.deduction[index]['mcc']" class="txt_mcc_value" />
                        <span class="txt_mcc_text">{{replaylist.detail.list[oindex].detail.deduction[index]['mcc_name']}}</span>
                        <i class="fa fa-chevron-down" style=""></i>
                    </div>

                    <div class="weui-cell__ft">
                        {{item.trade_time_explain}}
                    </div>

                </div>

            </div>
        </div>

        <button type="button" class="weui-btn weui-btn_primary btn_submit_plan" id="btn_submit_plan">提交还款计划</button>

    </div>

    <input  type="hidden" id="txt_mcc_select"  >
    <!--列表中天的下标-->
    <input type="hidden" id="mcc_index1">
    <!--一天中的明细交易下标-->
    <input type="hidden" id="mcc_index2">

    <!--日历 -->
    <div id="selfmask">
        <div id="dateview">
            <div style="font-size: 14px;width: 100%;height: 44px;line-height: 44px;">
                <a id="datecancel" class="mui-pull-left touch-action" style="left: 20px;color: #000000;position:absolute; z-index: 1000;"></a>
                <span class="mui-title" style="font-size: 15px;">请选择还款日期</span>
                <a id="dateconfirm" class="mui-pull-right touch-action" style="right: 20px;color: #000000;position:absolute; z-index : 1000;">确认</a>
            </div>
            <div id="scroll1" class="mui-scroll-wrapper" style="margin-top: 30px;">
                <div class="mui-scroll" id="mui-scroll">
                    <ul class="zh-circle" id="datesel">

                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="/static/jweui/js/city-picker-repay.js"  charset="utf-8"></script>
<script src="/static/mobile/js/res/mcc.js"  charset="utf-8"></script>
<script>

var ltoken='<?php echo htmlentities($token); ?>'
var cardid='<?php echo htmlentities($cardid); ?>'

    /* start vue 程序 *********/
    /**
     * 界面上计算数据
     */
    var now = new Date();
    now = formatDate(now);
    var repayment_set_vm1 = new Vue({
        el: '#div_set',
        data: {
            province: "福建省",
            city: "福州市",
            amount: null,
            count_per: 2,
            thistime: now,
            feerate: 0.78,
            fixamount: 0,
            select_date: "",
            total_count: 0,
            date_count: 0,
            require_amount: 0,
        },
        methods: {},
        watch: {
            amount: function(nval, oval) {

                this.total_count = this.date_count * this.count_per;
                this.fixamount = Math.round(this.feerate * nval) / 100 + this.date_count * this.count_per;
                this.require_amount = this.total_count != 0 ? Math.round(nval / this.total_count + this.fixamount + 100) : 0;

            },
            date_count: function(nv, ov) {
                this.total_count = nv * this.count_per;
                this.require_amount = this.total_count != 0 ? Math.round(this.amount / this.total_count + this.fixamount + 100) :
                    0;
                this.fixamount = Math.round(this.feerate * this.amount) / 100 + nv * this.count_per;
            },
            count_per: function(nv, ov) {
                this.total_count = nv * this.date_count;
                this.require_amount = this.total_count != 0 ? Math.round(this.amount / this.total_count + this.fixamount + 100) :
                    0;
                this.fixamount = Math.round(this.feerate * this.amount) / 100 + nv * this.date_count;
            }
        }
    });

    /**
     * 生成计划后绑定到页面上的数据
     */
    var repayment_set_vm2 = new Vue({
        el: '#div_create_list',
        data: {
            replaylist: null,
        },
        methods: {},
        watch: {
            replaylist: function(nv, ov) {
                //	console.log(JSON.stringify(nv));

            }
        }
    })


    /* end vue 程序 *********/



//选择每条交易记录的“行业”；
function pop_picker3(me) {
    var thisdiv = me;
   // console.log(me);
    var i1 = thisdiv.getAttribute("index1");
    var i2 = thisdiv.getAttribute("index2");
    console.log(i1+','+i2);

    //当前操作的位置下标保存起来；
    $('#mcc_index1').val(i1);
    $('#mcc_index2').val(i2);

   var current_value= $(thisdiv).find('.txt_mcc_text').text();

   console.log(current_value);

    $('#txt_mcc_select').val(current_value);



    //打开选择框，选择内容，然后在关闭的事件中进行赋值；
    $('#txt_mcc_select').select("open");

}

    $(function () {

        $('#txt_sel_city').cityPicker({
            title:"请选择消费城市",
            onClose:function (v) {
                console.log(v);
            }
        })


        //初始化一天还款笔数的picker
        $('#txt_count_per').picker({
            title:"请选择每日笔数",
            cols:[{
                textAlign:'center',
                values:['1 笔','2 笔']
            }],

            onClose:function (ec) {
               // console.log(ec);
              //  console.log(ec.value);

                var thisv=ec.value;
                if(thisv=="1 笔")
                {
                    repayment_set_vm1.$data.count_per=1;

                }
                else if(thisv=="2 笔")
                {
                    repayment_set_vm1.$data.count_per=2;
                }
            }
        })

        //
        $('#txt_mcc_select').select({
            title:"选择消费行业",
            items:mcc,
            onClose:function (v) {
                console.log(v)
                var i1=$('#mcc_index1').val();
                var i2=$('#mcc_index2').val();
                repayment_set_vm2.$data.replaylist.detail.list[i1].detail.deduction[i2]['mcc'] = v.data.values;
                repayment_set_vm2.$data.replaylist.detail.list[i1].detail.deduction[i2]['mcc_name'] = v.data.titles;
            }

        })

        //制定计划
        $("#btn_credit_submit").on("click", function() {

           $.showLoading("正在生成...")
            var total_amount = repayment_set_vm1.$data.amount;
            var sel_date = repayment_set_vm1.$data.select_date;

            if (total_amount == null || total_amount == "" || total_amount == 0) {
                $.alert("请填写还款金额！");
                $.hideLoading();
                return false;
            }

            if (sel_date == null || sel_date == "") {
                $.alert("请选择还款日期！");

                $.hideLoading();
                return false;
            }

            var data = {
                "token": ltoken,
                "bankcard_id": cardid,
                "total_amount": total_amount,
                "pay_channel_id": 1,
                "date": repayment_set_vm1.$data.select_date,
                "count": repayment_set_vm1.$data.count_per,
            }

            console.log(data);

            var url ='<?php echo url("/mobile/Repayment/customize"); ?>';
            //console.log(url);

            //生成还款计划；
            $.post(url,
                data,
                function(data) {
                    //console.log(JSON.stringify(data));
                    if (data.code == "0000") {
                        repayment_set_vm2.$data.replaylist = data.data;
                    } else {
                        $.alert(data.msg);
                    }

                    //repayment_set_vm1.$data.cards = data.data;
                    //console.log(JSON.stringify(data));
                    $.hideLoading();
                }, 'json')


        })

        //提交计划
        $("#btn_submit_plan").on("click", function() {

            $.showLoading("提交中...")

            if (!repayment_set_vm2.$data.replaylist || repayment_set_vm2.$data.replaylist == "null") {
                $.alert("请先制定还款计划！");
                $.hideLoading();
                return false;
            }

            var total_amount = repayment_set_vm2.$data.replaylist.total;
            if (total_amount == null || total_amount == "" || total_amount == 0) {
                $.alert("请填写还款金额！");
                $.hideLoading();
                return false;
            }

            var data2 = {
                "token": ltoken,
                "bankcard_id": cardid,
                "total_amount": repayment_set_vm2.$data.replaylist.total,
                "detail": JSON.stringify(repayment_set_vm2.$data.replaylist.detail.list),
                "pay_channel_id": 1,
                "province": repayment_set_vm1.$data.province,
                "city": repayment_set_vm1.$data.city,
                "channel_fee": repayment_set_vm2.$data.replaylist.detail.channel_fee,
                "fee": repayment_set_vm2.$data.replaylist.detail.fee,
                "count": repayment_set_vm2.$data.replaylist.detail.repay_count
            }



            var url = '<?php echo url("/mobile/Repayment/save"); ?>';
            //console.log(url);
            $.post(url,
                data2,
                function(data3) {
                    //console.log(JSON.stringify(data3));
                    if (data3.code == "0000") {

                        //提交计划到支付接口
                        var payurl = "http://pay2.xinzhima.cn/index/leshuapay/CreateRepayPlan";
                        var planid = data3.data;
                        var paydata = {
                            "token": ltoken,
                            "planid": planid,
                        }
                        $.post(payurl,
                            paydata,
                            function(rdata) {

                                //	console.log("rdata:" + JSON.stringify(rdata));

                                if (rdata.code == 1) {
                                    $.alert("计划提交完成", "温馨提示");
                                    $.hideLoading();
                                    //关闭当前页面；
                                    CreateKeeWindow.hideKeeWindow("win_repayment_setPlan");
                                    //打开计划列表页面；
                                    page_id = cardid;
                                    var winname = "win_syxq";
                                    var wintitle = "主任务列表";
                                    var winurl = "/mobile/Repayment/repaymentMainPlanView";
                                    var windata = {};
                                    CreateKeeWindow.openWindow(winname, wintitle, winurl, windata)

                                    
                                    //TODO
                                } else {
                                    $.alert(rdata.msg, "温馨提示")
                                    $.hideLoading();
                                }

                                $.hideLoading();
                            }
                        )

                    } else {
                        $.alert('计划保存失败', "温馨提示");
                        $.hideLoading();
                    }

                    //repayment_set_vm1.$data.cards = data.data;
                    //console.log(JSON.stringify(repayment_set_vm1.$data.cards));
                })

        })



        /***********选择日历******************/

            //初始化，固定显示30天；
            var thisdt = new Date().getTime();
            var thisd = 0; //日期
            var thism = 0; //月份
            //console.log(thism + "," + thisd);
            for (var i = 1; i <= 30; i++) {
                thisdt = thisdt + 24 * 3600 * 1000;
                //console.log(thisdt);
                newdt = new Date(thisdt);
                thisd = newdt.getDate();
                thism = newdt.getMonth() + 1; //由于月从0开始计,所以加1转成实际值
                $('#datesel').append('<li class="dateitem mui-table-view-cell" key="' + i + '" thisd="' + thisd + '" thism="' +
                    thism + '"><span>' + thisd + '</span><span>' + thism + '月</span></li>');
            }
            /*  初始化结束*/

            //连续选择
            $('#btn_selectdate_con').on("click", function() {

                var total_amount = repayment_set_vm1.$data.amount;
                if (total_amount == null || total_amount == "" || total_amount == 0) {
                    $.alert("请填写还款金额！")
                    return false;
                }

                $('#selfmask').show();
                $(this).addClass('bluebackgroud');
                $("#btn_selectdate_discon").removeClass("bluebackgroud");
            })

            //间隔选择
            $('#btn_selectdate_discon').on("click", function() {

                var total_amount = repayment_set_vm1.$data.amount;
                if (total_amount == null || total_amount == "" || total_amount == 0) {
                    $.alert("请填写还款金额！");
                    return false;
                }
                $('#selfmask').show();
                $(this).addClass('bluebackgroud');
                $("#btn_selectdate_con").removeClass("bluebackgroud");

            })

            //日期取消
            $('#datecancel').on('click', function() {
                //$('#selfmask').hide();
            })
            //日期确认
            $('#dateconfirm').on('click', function() {
                $('#selfmask').hide();
                var datelist = $('.isSelect');
                repayment_set_vm1.$data.date_count = datelist.length;

                //将选中的日期拼成字符串，放到repayment_set_vm1的data中
                var sel_date = "";
                var list=new Array();
                datelist.each(function(index, element) {
                    var dt = new Date();
                    var ty = dt.getFullYear();
                    var tm = $(this).attr("thism");
                    var td = $(this).attr("thisd");

                    list[index]=ty + "-" + tm + "-" + td;

                })
                repayment_set_vm1.$data.select_date = JSON.stringify(list);
                //console.log(sel_date);

            })

            //选中日期
            $('#dateview .dateitem').on("click", function() {

                if ($('#btn_selectdate_discon').hasClass("bluebackgroud")) {
                    //间隔选
                    if ($(this).hasClass('isSelect')) {
                        $(this).removeClass('isSelect');
                    } else {
                        $(this).addClass('isSelect');
                    }
                } else if ($('#btn_selectdate_con').hasClass("bluebackgroud")) {
                    //连续选
                    var selectlist = $(this).closest('ul').find('.isSelect');
                    if (selectlist.length == 1) {
                        var k1 = parseInt($(selectlist[0]).attr("key"));
                        var k2 = parseInt($(this).attr("key"));
                        //逆向选的话,就重新选;
                        if (k1 > k2) {
                            selectlist.removeClass('isSelect');
                            $(this).addClass('isSelect');
                        } else {
                            //选中两个之间的所有;
                            $(this).addClass('isSelect');
                            var list = $(selectlist[0]).nextUntil(this);
                            list.addClass('isSelect');
                        }
                    } else {
                        selectlist.removeClass('isSelect');
                        $(this).addClass('isSelect');
                    }
                }
            })


        /***************选择日历完*******************/
    })




</script>