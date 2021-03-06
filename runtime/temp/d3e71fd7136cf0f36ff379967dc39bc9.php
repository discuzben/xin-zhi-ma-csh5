<?php /*a:1:{s:85:"/cs/docker/nginx/html/csh5/application/mobile/view/repayment/repaymentplanDetail.html";i:1546912743;}*/ ?>

    <style>
		.table-view-cell div:first-child{
		    margin-bottom: 1rem;
		}
		.money{
		    color: #006d00;
		    font-size: 1.1rem;
		    position: absolute;
		    right: 0.5rem;
		}
		.bill{
		    color: #006d00;
		    font-size: 1.2rem;
		    font-weight: 200;
		}
		.other{
		    color: gray;
		}
		.table-view-cell{
		    margin-bottom: 0.5rem;
		    padding: 10px;
		}
		.status{
			float: right;
			font-size: 20px;
			margin-top: 5px;
		}
    </style>

<div class="page_planDetail">
    <ul class="table-view">
        <li class="table-view-cell" v-for = 'item in planDetailsList'>
            <div style="margin-bottom: 10px">
                <span class="money">{{item.money}}元</span>
                <div class="done-time">日期&nbsp;&nbsp;<span class="date-show">{{item.timestamp}}</span></div>
            </div>
            <div>
                <span class="bill">{{item.trans_type}}</span>
                <span class="other">({{item.desc}})</span>
				<p class="status font_family icon-zhixingzhong" style="color:#FFD14B" v-if = 'item.status == 0'>执行中</p>
				<p class="status font_family icon-zhixingzhong" style="color:#FFD14B" v-else-if = 'item.status == 1'>执行中</p>
				<p class="status font_family icon-zhixingchenggong" style="color: #71E9A4" v-else-if = 'item.status == 2'>执行成功</p>
				<p class="status font_family icon-shibai" style="color:#F16541" v-else-if = 'item.status == 9'>执行失败</p>
				<p class="status font_family icon-zhixingzhong" style="color:#FFD14B" v-else>执行中</p>
            </div>
        </li>
    </ul>
</div>

<script>
	(function(){
		new Vue({
			el: '.page_planDetail',
			data: {
				planDetailsList:[],
				page:1
			},
			mounted(){
				this.getMainPlan();
			},
			methods: {
				getMainPlan(){
					let getMainPlanUrl = '/mobile/Repayment/details';
					$.showLoading();
					let self = this;
					$.post({
					    url: getMainPlanUrl,
					    data: self.getPostData(),
					    success(res) {
					    	console.log("计划详情:");
					    	console.log(res);
					    	let curdata = res.data;
					    	self.planDetailsList = curdata;
					    	$.hideLoading();
					    }
					});
				},
		         getPostData(){
                    let postData = {};
                    postData.id = page_id;
                    return postData;
                }
			}
		});
	})();
</script>
</body>

</html>
