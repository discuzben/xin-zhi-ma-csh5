<?php /*a:1:{s:83:"/cs/docker/nginx/html/csh5/application/mobile/view/repayment/repaymentMainPlan.html";i:1546912743;}*/ ?>

    <style>
    	.page_repaymentMainPlan .done-time>span,.plan-money>span,.actual-money>span{
		    margin-left: 2.5rem;
		}
		.page_repaymentMainPlan .plan-money>span,.actual-money>span{
		    font-size: 1.4rem;
		    color: red;
		}
		.page_repaymentMainPlan .done-time{
		    /*background-color: #e7136a;*/
		}
		.page_repaymentMainPlan .table-view-cell{
			padding: 10px;
            border-bottom: 1px solid #E5E5E5;
		}
		.page_repaymentMainPlan .table-view-cell>div{
		    margin: 0.2rem 0 0.6rem 0.2rem;
		}
		.page_repaymentMainPlan .status{
			float: right;
			font-size: 20px;
			margin-top: -35px;
		}
		.page_repaymentMainPlan .pull-bottom-tips {
			font-size: 15px;
		}
    </style>

    <div class="page_repaymentMainPlan">
        <ul class="table-view" id="mainPlan-view">
            <li class="table-view-cell" class="main_plan_li" v-for = 'item in planmainList' @click="openPlanDetail(item)">
                <div class="done-time">执行日期&nbsp;&nbsp;<span>{{item.p_time_explain}}</span></div>
                <div class="plan-money">计划金额&nbsp;&nbsp;<span>{{item.p_amount_explain}}</span></div>
                <div class="actual-money">实际金额&nbsp;&nbsp;<span>{{item.a_amount_explain}}</span>
	                <div class="pull-bottom-tips">
						<p class="status font_family icon-zhixingzhong" style="color:#FFD14B" v-if = 'item.status == 0'>执行中</p>
						<p class="status font_family icon-zhixingzhong" style="color:#FFD14B" v-else-if = 'item.status == 1'>执行中</p>
						<p class="status font_family icon-zhixingchenggong" style="color: #71E9A4" v-else-if = 'item.status == 2'>执行成功</p>
						<p class="status font_family icon-zhixingchenggong" style="color: #71E9A4" v-else-if = 'item.status == 3'>部分成功</p>
						<p class="status font_family icon-shibai" style="color:#F16541" v-else-if = 'item.status == 4'>提交失败</p>
						<p class="status font_family icon-shibai" style="color:#F16541" v-else-if = 'item.status == 9'>执行失败</p>
						<p class="status font_family icon-zhixingzhong" style="color:#FFD14B" v-else>执行中</p>
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

	<script>
		(function(){
			new Vue({
				el: '.page_repaymentMainPlan',
				data: {
					planmainList:[],
					page:1,
					nomore:false,
					loading:false
				},
				mounted(){
					this.getMainPlan($(".page_repaymentMainPlan"));
					// 绑定上拉刷新
                    let self = this;
                    global.scroll($(".page_repaymentMainPlan"),function(ds){
                         console.log('sdf')
                        //到达底部需要执行的内容
                        self.getMainPlan(ds);
                    });
				},
				methods: {
					getMainPlan(ds){
						let getMainPlanUrl = '/mobile/Repayment/orders';
						if(this.loading) return;
                        this.loading = true;
						let self = this;
						$.post({
						    url: getMainPlanUrl,
						    data: self.getPostData(),
						    success(res) {
						    	console.log("计划主列表:");
						    	console.log(res);
						    	let curdata = res.data.data;

						    	if (self.nomore === true) {
                                    return;
                                }
                                if (res.data.max_page === res.data.page) {
                                    // 没数据移除加载更多显示没有更多数据了
                                    self.nomore = true;
                                    console.log("没有更多数据了");
                                    ds.find('.weui-loadmore').remove();
                                    ds.find('.loadnomore').show();
                                }
						    	
						    	self.planmainList = curdata;
						    	self.loading = false;
						    }
						});
					},
					openPlanDetail(it){
						page_id = it.id;
		                var winname = "win_jhxq";
		                var wintitle = "计划详情";
		                var winurl = "/mobile/Repayment/repaymentplanDetailView";
		                var windata = {};
		                CreateKeeWindow.openWindow(winname, wintitle, winurl, windata)
			        },
			         getPostData(){
                        let postData = {};
                        postData.id = page_id;
                        postData.page = this.page++;
                        return postData;
                    }
				}
			});
		})();
	</script>
</body>

</html>
