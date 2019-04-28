<?php /*a:1:{s:83:"/cs/docker/nginx/html/csh5/application/mobile/view/repayment/repaymentPlanList.html";i:1546912743;}*/ ?>


    <style>
        .page_repaymentPlanList{
            height: 100%;
        }
        .page_repaymentPlanList .list-top{
            width: 100%;
            height: 8rem;
            float: left;
            background-color: #729eff;
            color: white;
        }
        .page_repaymentPlanList .icon-shibai,.icon-zhixingzhong,.icon-zhixingchenggong{
            font-size: 15px;
        }
        .page_repaymentPlanList .all-repayment,.all-consumption{
            float: left;
            width: 50% !important;
            height: 8rem;
        }
        .page_repaymentPlanList .list-top .inp {
            width: 100%;
            float: left;
            outline: none;
            -webkit-appearance: none;
            border-radius: 0;
            text-align: center;
            background: none;
            font-size: 1.5rem;
            height: 5rem;
            line-height: 6rem;
            border: none;
            padding: 0;
        }
        .page_repaymentPlanList .show{
            width: 100%;
            float: left;
            text-align: center;
            font-size: 0.8rem;
        }
        .page_repaymentPlanList .repayment-li{
            /* 	width:100%;
                height: 15rem; */
        }
        .page_repaymentPlanList .repaymeny-li-top{
            height: 1.6rem;
            line-height: 1.6rem;
            text-align: left;
            font-size: 0.8rem;
            background-color: #eff4f7;
            padding: 0 10px;
            box-sizing: border-box;
        }
        .page_repaymentPlanList .repaymeny-li-middle{
            width: 100%;
            padding: 0 10px;
            box-sizing: border-box;
            height: 9rem;
            /*background-color: #e7136a;*/
            /*border-bottom: 1px lightgrey solid;*/
        }
        .page_repaymentPlanList .repaymeny-li-middle>div{
            float: left;
            width: 50%;
            margin: 0.5rem 0;
            /*font-size: 1rem;*/
        }
        .page_repaymentPlanList .repaymeny-li-bottom{
            /*border-bottom: 1px lightgrey solid;*/
            /* position: absolute; */
            width: 100%;
            float: left;
            vertical-align: middle;
        }
        .page_repaymentPlanList .weui-navbar + .weui-tab__bd{
            padding-top: 0;
        }
        .page_repaymentPlanList .weui-tab__bd{
            height: none;
        }
        .page_repaymentPlanList .repaymeny-li-bottom-status{
            height: 100%;
            float: left;
            margin-top: 8px;
        }
        .page_repaymentPlanList .weui-tab__bd-item{
            overflow: auto;
        }
        .page_repaymentPlanList .weui-navbar .icon-zhixingchenggong:before{
            color:#FFD14B;
        }
        .page_repaymentPlanList .weui-navbar .icon-zhixingzhong:before{
            color:#71E9A4;
        }
        .page_repaymentPlanList .weui-navbar .icon-shibai:before{
            color:#F16541;
        }
    </style>

<div class="page_repaymentPlanList">
    <div class="list-top" id="list-top">
        <div class="all-repayment">
            <span class="inp">{{topForm.repayment_amount}}</span>
            <div class="show">总还款金(元)</div>
        </div>
        <div class="all-consumption">
            <span class="inp">{{topForm.consumption_amount}}</span>
            <div class="show">总消费金额(元)</div>
        </div>
    </div>
    <!--标题选择栏目-->
    <div class="weui-tab">
        <div class="weui-navbar" style="position: static;top: auto;">
            <a class="weui-navbar__item weui-bar__item--on" href="#payment-list-tab1">
                全部
            </a>
            <a class="weui-navbar__item font_family icon-zhixingchenggong" href="#payment-list-tab2">
                执行成功
            </a>
            <a class="weui-navbar__item font_family icon-zhixingzhong" href="#payment-list-tab3">
                执行中
            </a>
            <a class="weui-navbar__item font_family icon-shibai" href="#payment-list-tab4">
                失败
            </a>
        </div>
        <div class="weui-tab__bd">
            <div id="payment-list-tab1" class="weui-tab__bd-item weui-tab__bd-item--active" ontouchstart name = "all">
                <ul class="table-view" id="list-main">
                    <li class="table-view-cell" v-for="item in listAllForm.planList" @click="openMainPlan(item)">
                        <div class="repayment-li">
                            <div class="repaymeny-li-top">提交日期&nbsp;&nbsp;<span style="color: #fb6843">{{item.create_time_explain}}</span></div>
                            <div class="repaymeny-li-middle">
                                <div>消费总金额&nbsp;&nbsp;<span style="color: #fb6843">{{item.consumption_amount}}</span></div>
                                <div>还款总金额&nbsp;&nbsp;<span style="color: #fb6843">{{item.repayment_amount}}</span></div>
                                <div>消费手续费&nbsp;&nbsp;<span style="color: #fb6843">{{item.consumption_fee}}</span></div>
                                <div>还款手续费&nbsp;&nbsp;<span style="color: #fb6843">{{item.repayment_fee}}</span></div>
                                <div>消费总费率&nbsp;&nbsp;<span style="color: #fb6843">{{item.channel_rate}}</span></div>
                                <p class="repaymeny-li-bottom">
                                    <span :class="item.icon_status" :style="item.icon_style" style="float: left;"></span>
                                    <span class="repaymeny-li-bottom-status">{{item.status_explain}}</span>
                                </p>
                            </div>
                        </div>
                    </li>
                </ul>
                <div class="weui-loadmore">
                    <i class="weui-loading"></i>
                    <span class="weui-loadmore__tips">正在加载</span>
                </div>
                <p class="loadnomore" style="font-size: 14px;text-align: center;display: none;width: 100%; height: 65px;line-height: 65px">没有更多数据了</p>
            </div>
            <div id="payment-list-tab2" class="weui-tab__bd-item" ontouchstart name = "success">
                <ul class="table-view" id="exe-success">
                    <li class="table-view-cell"  v-for="item in listSuccessForm.planList" @click="openMainPlan(item)">
                        <div class="repayment-li">
                            <div class="repaymeny-li-top">提交日期&nbsp;&nbsp;<span style="color: #fb6843">{{item.create_time_explain}}</span></div>
                            <div class="repaymeny-li-middle">
                                <div>消费总金额&nbsp;&nbsp;<span style="color: #fb6843">{{item.consumption_amount}}</span></div>
                                <div>还款总金额&nbsp;&nbsp;<span style="color: #fb6843">{{item.repayment_amount}}</span></div>
                                <div>消费手续费&nbsp;&nbsp;<span style="color: #fb6843">{{item.consumption_fee}}</span></div>
                                <div>还款手续费&nbsp;&nbsp;<span style="color: #fb6843">{{item.repayment_fee}}</span></div>
                                <div>消费总费率&nbsp;&nbsp;<span style="color: #fb6843">{{item.channel_rate}}</span></div>
                                <p class="repaymeny-li-bottom">
                                    <span class="font_family icon-zhixingchenggong" style="color: #71E9A4;font-size: 1.4rem !important;float: left;"></span>
                                    <span class="repaymeny-li-bottom-status">{{item.status_explain}}</span>
                                </p>
                            </div>
                        </div>
                    </li>
                </ul>
                <div class="weui-loadmore">
                    <i class="weui-loading"></i>
                    <span class="weui-loadmore__tips">正在加载</span>
                </div>
                <p class="loadnomore" style="font-size: 14px;text-align: center;display: none;width: 100%; height: 65px;line-height: 65px">没有更多数据了</p>
            </div>
            <div id="payment-list-tab3" class="weui-tab__bd-item"  ontouchstart name = "executing">
                <ul class="table-view" id="executing" ontouchstart>
                    <li class="table-view-cell" v-for="item in listExecutingForm.planList" @click="openMainPlan(item)">
                        <div class="repayment-li">
                            <div class="repaymeny-li-top">提交日期&nbsp;&nbsp;<span style="color: #fb6843">{{item.create_time_explain}}</span></div>
                            <div class="repaymeny-li-middle">
                                <div>消费总金额&nbsp;&nbsp;<span style="color: #fb6843">{{item.consumption_amount}}</span></div>
                                <div>还款总金额&nbsp;&nbsp;<span style="color: #fb6843">{{item.repayment_amount}}</span></div>
                                <div>消费手续费&nbsp;&nbsp;<span style="color: #fb6843">{{item.consumption_fee}}</span></div>
                                <div>还款手续费&nbsp;&nbsp;<span style="color: #fb6843">{{item.repayment_fee}}</span></div>
                                <div>消费总费率&nbsp;&nbsp;<span style="color: #fb6843">{{item.channel_rate}}</span></div>
                                <p class="repaymeny-li-bottom">
                                    <span class="font_family icon-zhixingzhong" style="color: #FFD14B;font-size: 1.4rem !important;float: left;"></span>
                                    <span class="repaymeny-li-bottom-status">{{item.status_explain}}</span>
                                </p>
                            </div>
                        </div>
                    </li>
                </ul>
                <div class="weui-loadmore">
                    <i class="weui-loading"></i>
                    <span class="weui-loadmore__tips">正在加载</span>
                </div>
                <p class="loadnomore" style="font-size: 14px;text-align: center;display: none;width: 100%; height: 65px;line-height: 65px">没有更多数据了</p>
            </div>
            <div id="payment-list-tab4" class="weui-tab__bd-item" ontouchstart name = "fail">
                <ul class="table-view" id="fail" ontouchstart>
                    <li class="table-view-cell" v-for="item in listFailForm.planList" @click="openMainPlan(item)">
                        <div class="repayment-li">
                            <div class="repaymeny-li-top">提交日期&nbsp;&nbsp;<span style="color: #fb6843">{{item.create_time_explain}}</span></div>
                            <div class="repaymeny-li-middle">
                                <div>消费总金额&nbsp;&nbsp;<span style="color: #fb6843">{{item.consumption_amount}}</span></div>
                                <div>还款总金额&nbsp;&nbsp;<span style="color: #fb6843">{{item.repayment_amount}}</span></div>
                                <div>消费手续费&nbsp;&nbsp;<span style="color: #fb6843">{{item.consumption_fee}}</span></div>
                                <div>还款手续费&nbsp;&nbsp;<span style="color: #fb6843">{{item.repayment_fee}}</span></div>
                                <div>消费总费率&nbsp;&nbsp;<span style="color: #fb6843">{{item.channel_rate}}</span></div>
                                <p class="repaymeny-li-bottom">
                                    <span class="font_family icon-shibai" style="color: #F16541;font-size: 1.4rem !important;float: left;"></span>
                                    <span class="repaymeny-li-bottom-status">{{item.status_explain}}</span>
                                </p>
                            </div>
                        </div>
                    </li>
                </ul>
                <div class="weui-loadmore">
                    <i class="weui-loading"></i>
                    <span class="weui-loadmore__tips">正在加载</span>
                </div>
                <p class="loadnomore" style="font-size: 14px;text-align: center;display: none;width: 100%; height: 65px;line-height: 65px">没有更多数据了</p>
            </div>
        </div>
    </div>
<div>

    <script>
        (function () {
            $(function() {
                FastClick.attach(document.getElementById('tab1').getElementsByClassName('table-view')[0]);
            });
            new Vue({
                el: '.page_repaymentPlanList',
                data: {
                    topForm:{
                        consumption_amount:'0.00',
                        repayment_amount:'0.00'
                    },
                    listAllForm:{
                        planList:[],
                        page:1,
                        loading:false,
                        nomore:false
                    },
                    listSuccessForm:{
                        planList:[],
                        page:1,
                        loading:false,
                        nomore:false
                    },
                    listExecutingForm:{
                        planList:[],
                        page:1,
                        loading:false,
                        nomore:false
                    },
                    listFailForm:{
                        planList:[],
                        page:1,
                        loading:false,
                        nomore:false
                    }
                },
                mounted(){
                    this.getStatics();

                    // 动态设置滚动内容高度
                    let tabHeight = $(document).height()-$('.window_head').height()-$('#list-top').height()-$('.weui-navbar').height();
                    $('.page_repaymentPlanList .weui-tab__bd').css('height',tabHeight +'px');

                    // 第一次初始化
                    this.allRender($(".page_repaymentPlanList #payment-list-tab1"));
                    this.successRender($(".page_repaymentPlanList #payment-list-tab2"));
                    this.executingRender($(".page_repaymentPlanList #payment-list-tab3"));
                    this.failRender($(".page_repaymentPlanList #payment-list-tab4"));

                    // 绑定上拉刷新
                    let self = this;
                    global.scroll($(".page_repaymentPlanList .weui-tab__bd-item"),function(ds){
                         console.log('sdf')
                        //到达底部需要执行的内容
                        var btn_name = ds.attr("name");
                        switch (btn_name) {

                            case "all":
                                //加载所有部分
                                self.allRender(ds);
                            break;
                            case "success":
                                self.successRender(ds);
                            break;
                            case "executing":
                                self.executingRender(ds);
                            break;
                            case "fail":
                                self.failRender(ds);
                            break;
                            default:
                            break;  
                        }
                    });
                },
                methods:{
                    getStatics(){
                        let statisticstUrl = '/mobile/Repayment/statistics';
                        let self = this;
                        $.post({
                            url: statisticstUrl,
                            success(res) {
                                console.log(res);
                                let curdata = res.data;
                                self.topForm.consumption_amount = curdata.consumption_amount == null?'0.00':curdata.consumption_amount;
                                self.topForm.repayment_amount = curdata.repayment_amount == null?'0.00':curdata.repayment_amount;
                            }
                        });
                    },
                    getPlan(pd,ty,t){
                        let planstUrl = '/mobile/Repayment/plans';
                        let self = this;
                        $.post({
                            url: planstUrl,
                            data:pd,
                            success(res) {
                                console.log(res);
                                let curdata = res.data.data;
                                switch(ty){
                                    case '1':
                                        if (self.listAllForm.nomore === true) {
                                            return;
                                        }
                                        if (res.data.max_page <= res.data.page) {
                                            // 没数据移除加载更多显示没有更多数据了
                                            self.listAllForm.nomore = true;
                                            console.log("没有更多数据了");
                                            $('.page_repaymentPlanList #payment-list-tab1 .weui-loadmore').remove();
                                            $('.page_repaymentPlanList #payment-list-tab1 .loadnomore').show();
                                        }
                                        self.listAllForm.planList = self.listAllForm.planList.concat(self.dealStyle(curdata));
                                    break;
                                    case '2':
                                        if (self.listSuccessForm.nomore === true) {
                                            return;
                                        }
                                        if (res.data.max_page === res.data.page) {
                                            // 没数据移除加载更多显示没有更多数据了
                                            self.listSuccessForm.nomore = true;
                                            console.log("没有更多数据了");
                                            $('.page_repaymentPlanList #payment-list-tab2 .weui-loadmore').remove();
                                            $('.page_repaymentPlanList #payment-list-tab2 .loadnomore').show();
                                        }
                                        self.listSuccessForm.planList = self.listSuccessForm.planList.concat(curdata);
                                    break;
                                    case '3':
                                        if (self.listExecutingForm.nomore === true) {
                                            return;
                                        }
                                        if (res.data.max_page === res.data.page) {
                                            // 没数据移除加载更多显示没有更多数据了
                                            self.listExecutingForm.nomore = true;
                                            console.log("没有更多数据了");
                                            $('.page_repaymentPlanList #payment-list-tab3 .weui-loadmore').remove();
                                            $('.page_repaymentPlanList #payment-list-tab3 .loadnomore').show();
                                        }
                                        self.listExecutingForm.planList = self.listExecutingForm.planList.concat(curdata);
                                    break;
                                    default:
                                        if (self.listFailForm.nomore === true) {
                                            return;
                                        }
                                        if (res.data.max_page === res.data.page) {
                                            // 没数据移除加载更多显示没有更多数据了
                                            self.listFailForm.nomore = true;
                                            console.log("没有更多数据了");
                                            $('.page_repaymentPlanList #payment-list-tab4 .weui-loadmore').remove();
                                            $('.page_repaymentPlanList #payment-list-tab4 .loadnomore').show();
                                        }
                                        self.listFailForm.planList = self.listFailForm.planList.concat(curdata);
                                }
                            }
                        });
                    },
                    getPostData(status){
                        let postData = {};
                        console.log(page_id)
                        postData.pay_channel_id = 1;
                        postData.bankcard_id = page_id;
                        postData.status = status;
                        switch (status) {
                            case 'all':
                                postData.page = this.listAllForm.page++;
                            break;
                            case '2':
                                postData.page = this.listSuccessForm.page++;
                            break;
                            case '1':
                                postData.page = this.listExecutingForm.page++;
                            break;
                            default:
                                postData.page = this.listFailForm.page++;
                        }

                        return postData;
                    },
                    dealStyle(cd){
                        $.each(cd,function(index,item){
                            if(item.status == '1'){
                                item['icon_status'] = 'font_family icon-zhixingzhong';
                                item['icon_style'] = 'color:#FFD14B;font-size: 1.4rem !important;';
                            }else if(item.status == '2'){
                                item['icon_status'] = 'font_family icon-zhixingchenggong';
                                item['icon_style'] = 'color: #71E9A4;font-size: 1.4rem !important;';
                            }else if(item.status == '9'){
                                item['icon_status'] = 'font_family icon-shibai';
                                item['icon_style'] = 'color:#F16541;font-size: 1.4rem !important;';
                            }
                        });
                        return cd;
                    },
                    openMainPlan(it){
                        page_id = it.id;
                        var winname = "win_syxq";
                        var wintitle = "主任务列表";
                        var winurl = "/mobile/Repayment/repaymentMainPlanView";
                        var windata = {};
                        CreateKeeWindow.openWindow(winname, wintitle, winurl, windata)
                    },
                    allRender(t){
                        console.log("t:"+t);
                        if(this.listAllForm.loading) return;
                        this.listAllForm.loading = true;
                        let self = this;
                        setTimeout(function() {
                            self.getPlan(self.getPostData('all'),'1',t);
                            self.listAllForm.loading = false;
                        }, 1500);
                    },
                    successRender(t){
                        if(this.listSuccessForm.loading) return;
                        this.listSuccessForm.loading = true;
                        let self = this;
                        setTimeout(function() {
                            self.getPlan(self.getPostData(2),'2',t);
                            self.listSuccessForm.loading = false;
                        }, 1500);
                    },
                    executingRender(t){
                        if(this.listExecutingForm.loading) return;
                        this.listExecutingForm.loading = true;
                        let self = this;
                        setTimeout(function() {
                            self.getPlan(self.getPostData(1),'3',t);
                            self.listExecutingForm.loading = false;
                        }, 1500);   //模拟延迟
                    },
                    failRender(t){
                       if(this.listFailForm.loading) return;
                        this.listFailForm.loading = true;
                        let self = this;
                        setTimeout(function() {
                            self.getPlan(self.getPostData(9),'4',t);
                            self.listFailForm.loading = false;
                        }, 1500);   //模拟延迟 
                    }
                }
            });
        })();
    </script>
</div>
