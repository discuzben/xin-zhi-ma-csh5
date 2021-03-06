<?php /*a:1:{s:67:"/cs/docker/nginx/html/csh5/application/mobile/view/fund/income.html";i:1545998117;}*/ ?>

		<style>

			.page_income #btn-my-earning{
				color: white;
				font-size: 12px;
				float: right;
				/* margin-right: 10px; */
			}
			.page_income #mybalance-num{
				background-color: #1B99DC;
				margin-top: 1px;
			}
			.page_income .mybalance-num-title{
				background-color: #1B99DC;
				padding-top:35px;
				display: block;
				margin: 0px 15px;
				color: gold;
				text-align: center;
			}
			.page_income .mybalance-num{
				background-color: #1B99DC;
				color: white;
				display: block;
				padding-bottom: 20px;
				margin: 0px 15px;
				font-size: 32px;
				text-align: center;
			}
			.page_income #earn-type{
				display: -webkit-flex;
				display: flex;
				justify-content: space-between;
				margin-top: 10px;
				height: 70px;
				color: gold;
				/* margin:0 20px; */
			}
			.page_income #earn-type-left,#earn-type-right{
				text-align: center;
				width: 50%;
			}
			.page_income #withdrawable-earn{
				color: white;
				text-align: center;
				font-size: 17px;
				margin-top: 10px;
			}
			.page_income #dayearn{
				background-color: white;
				padding: 15px;
				border-top: 1px solid white;
				border-bottom: 1px solid rgba(200,200,200,0.2);
			}
		</style>
	</head>

	<body>
		<div class="page_income">
			<div id="mybalance-num">
				<span class="mybalance-num-title">累计收益(元)</span><br>
				<span class="mybalance-num">{{form.total_income_fy==null?'0.00':form.total_income_fy}}</span>
				<div id="earn-type">
					<div id="earn-type-left">
						<span id="withdrawable-earn-title">
							当月收益（元）
						</span><br>
						<p id="withdrawable-earn">
							{{form.income_for_month==null?'0.00':form.income_for_month}}
						</p>
					</div>
					<div id="earn-type-right">
						<span id="withdrawable-earn-title">
							今日收益（元）
						</span><br>
						<p id="withdrawable-earn">
							{{form.income_for_day==null?'0.00':form.income_for_day}}
						</p>
					</div>
				</div>
			</div>
			<div class="touch-action" id="dayearn">每日收益</div>
				<div class="weui-cells" style="margin-top: 0">

	                <todo-item v-for="item in earnList" v-bind:todo="item" v-bind:key="item.id">
					</todo-item>

		        </div>
		    </div>
		</div>

		<script type="text/javascript">
				(function(){
					Vue.component('todo-item', {
						props: ['todo'],
						template: '<div class="weui-cell weui-cell_access">\
										<div class="weui-cell__bd">\
									        <p>{{ todo.date }}</p>\
									    </div>\
									    <div class="weui-cell__ft">{{ todo.amount }}</div>\
				                	</div>'
					});
					new Vue({
						el: '.page_income',
						data: {
							form:{
								total_income_fy: '0.00',
								income_for_month: '0.00',
								income_for_day: '0.00',
							},
							earnList: []
						},
						mounted(){
							this.getUserInfo();
							this.getDayIncome();
						},
						methods: {
							getUserInfo(){
								let userUrl = '/mobile/user/user';
								$.showLoading();
								let self = this;
								$.post({
								    url: userUrl,
								    success(res) {
								    	console.log("累计，当月，今日:")
								    	console.log(res)
								    	let curdata = res.data;
								    	self.form.total_income_fy = curdata.total_income_fy==0?'0.00':curdata.total_income_fy;
								    	self.form.income_for_month = curdata.income_for_month==0?'0.00':curdata.income_for_month;
								    	self.form.income_for_day = curdata.income_for_day==0?'0.00':curdata.income_for_day;
								    	$.hideLoading();
								    }
								});
							},
							getDayIncome(){
								let getDayIncomeUrl = '/mobile/Fund/incomeForDay';
								$.showLoading();
								let self = this;
								$.post({
								    url: getDayIncomeUrl,
								    success(res) {
								    	console.log("每日收益:")
								    	console.log(res)
								    	let curdata = res.data.data;
								    	self.earnList = curdata;
								    	$.hideLoading();
								    }
								});
							},
							openIncomeDetail(){

				                var winname = "win_syxq";
				                var wintitle = "收益详情";
				                var winurl = "/mobile/fund/incomeDetailView";
				                var windata = {};
				                CreateKeeWindow.openWindow(winname, wintitle, winurl, windata)
					        }
						}
					});
				})();

			
		</script>
	</body>

</html>
