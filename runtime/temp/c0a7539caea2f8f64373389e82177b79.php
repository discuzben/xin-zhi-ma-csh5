<?php /*a:1:{s:68:"/cs/docker/nginx/html/csh5/application/mobile/view/fund/balance.html";i:1545735395;}*/ ?>

		<style>
			.page_balance .mui-bar-nav{
				line-height: 44px;
			}
			.page_balance #btn-my-balance{
				color: white;
				font-size: 12px;
				float: right;
				/* margin-right: 10px; */
			}
			.page_balance .font_family{
				color: #1B99DC;
			}
		</style>
	</head>

		<div class="page_balance">
			<div id="mybalance-num" style="background-color: #1B99DC;margin-top: 1px;">
				<span style="background-color: #1B99DC;padding-top:35px;display: block;padding-bottom: 20px;margin: 0px 15px;color: gold;">账户余额(元)</span>
				<span style="background-color: #1B99DC;color: white; display: block; margin: 0px 15px;font-size: 50px;padding-bottom: 15px">{{form.balance}}</span>
			</div>
			<div class="weui-cells" style="margin-top: 0">
				<div class="weui-cell" name="yetx">
		            <div class="weui-cell__hd"><i class="font_family icon-yuetixian"></i></div>
		            <div class="weui-cell__bd">
		                <p>余额提现</p>
		            </div>
		            <div class="weui-cell__ft"><i class="fa fa-chevron-right" ></i></div>
		        </div>
		        <div class="weui-cell" name="txjl">
		            <div class="weui-cell__hd"><i class="font_family icon-zhuanzhangjilu"></i></div>
		            <div class="weui-cell__bd">
		                <p>提现记录</p>
		            </div>
		            <div class="weui-cell__ft"><i class="fa fa-chevron-right" ></i></div>
		        </div>
		    </div>
		</div>

		<script type="text/javascript">
			(function(){
				new Vue({
					el: '.page_balance',
					data: {
						form:{
							balance:'0.00'
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
							    	console.log(res)
							    	let curdata = res.data;
							    	self.form.balance = curdata.balance;
							    	$.hideLoading();
							    }
							});
						},
						bindEvent(){
							$('.page_balance .weui-cell').on("click",function () {

						        $.showLoading();

						        var btn_name = $(this).attr("name");
						        switch (btn_name) {

						            case "yetx":
						                //我的收益
						                var winname = "win_wdsy";
						                var wintitle = "余额提现";
						                var winurl = "/mobile/fund/withdrawView";
						                var windata = {};
						                CreateKeeWindow.openWindow(winname, wintitle, winurl, windata)
						                break;

						            case "txjl":
						                //我的余额
						                var winname = "win_wdye";
						                var wintitle = "提现记录";
						                var winurl = "/mobile/fund/withdrawLogView";
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
