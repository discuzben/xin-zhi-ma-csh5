<?php /*a:1:{s:82:"/cs/docker/nginx/html/csh5/application/mobile/view/repayment/repaymentManager.html";i:1546912743;}*/ ?>


		<style>
		.page_repaymentManager .doller{
			margin-top: 4px;
			color: gold;
		}
		.page_repaymentManager .font-family{
			color: goldenrod;
		}
		</style>
	</head>

		<div class="page_repaymentManager">
			<div class="weui-cells" style="margin-top: 0">
				<div class="weui-cell weui-cell_access" name="wdsy">
		            <div class="weui-cell__hd"><i class="font_family icon-wodeshouyi"></i></div>
		            <div class="weui-cell__bd">
		                <p>我的收益</p>
		            </div>
		            <div class="weui-cell__ft"><span class="doller">￥{{form.balance}}</span></div>
		        </div>
		        <div class="weui-cell weui-cell_access" name="wdye">
		            <div class="weui-cell__hd"><i class="font_family icon-wodeyue"></i></div>
		            <div class="weui-cell__bd">
		                <p>我的余额</p>
		            </div>
		            <div class="weui-cell__ft"><span class="doller">￥{{form.income}}</span></div>
		        </div>
		        <div class="weui-cell weui-cell_access" name="xykgl">
		            <div class="weui-cell__hd"><i class="font_family icon-wodeqiabao"></i></div>
		            <div class="weui-cell__bd">
		                <p>信用卡管理</p>
		            </div>
		            <div class="weui-cell__ft"></div>
		        </div>
		        <div class="weui-cell weui-cell_access" name="hylb">
		            <div class="weui-cell__hd"><i class="font_family icon-haoyouliebiao"></i></div>
		            <div class="weui-cell__bd">
		                <p>好友列表</p>
		            </div>
		            <div class="weui-cell__ft"></div>
		        </div>
		    </div>
		</div>

		<script type="text/javascript">
			(function(){
				new Vue({
					el: '.page_repaymentManager',
					data: {
						form:{
							balance:'0.00',
							income:'0.00'
						}
					},
					mounted(){
						this.getUserInfo();
						this.bindEvent();
					},
					methods: {
						getUserInfo(){
							let userUrl = '/mobile/user/user';
							$.showLoading();
							let self = this;
							$.post({
							    url: userUrl,
							    success(res) {
							    	let curdata = res.data;
							    	self.form.balance = curdata.balance;
							    	self.form.income = curdata.total_income;
							    	console.log(res)
							    	$.hideLoading();
							    }
							});
						},
						bindEvent(){
							$('.page_repaymentManager .weui-cell').on("click",function () {

						        $.showLoading();

						        var btn_name = $(this).attr("name");
						        switch (btn_name) {

						            case "wdsy":
						                //我的收益
						                var winname = "win_wdsy";
						                var wintitle = "我的收益";
						                var winurl = "/mobile/fund/incomeView";
						                var windata = {};
						                CreateKeeWindow.openWindow(winname, wintitle, winurl, windata)
						                break;
						            case "wdye":
						                //我的余额
						                var winname = "win_wdye";
						                var wintitle = "我的余额";
						                var winurl = "/mobile/fund/balanceView";
						                var windata = {};
						                CreateKeeWindow.openWindow(winname, wintitle, winurl, windata)
						                break;


						            case "xykgl":
						                //还款管理
						                var winname = "win_xykgl";
						                var wintitle = "信用卡管理";
						                var winurl = "/mobile/repayment/cardManagerView";
						                var windata = {};
						                CreateKeeWindow.openWindow(winname, wintitle, winurl, windata)
						                break;
						            case "hylb":

						                //好友列表
						                var winname = "win_hylb";
						                var wintitle = "好友列表";
						                var winurl = "/mobile/user/friendView";
						                var windata = {};
						                CreateKeeWindow.openWindow(winname, wintitle, winurl, windata)
						                break;

						            default:
						                $.hideLoading();
						                break;
						        }
						    })
						}
					}
				});
			})();
		</script>
	</body>

</html>
