<?php /*a:1:{s:69:"/cs/docker/nginx/html/csh5/application/mobile/view/fund/withdraw.html";i:1545735395;}*/ ?>

		<style>
			.page_withdraw .card-content{
				background-color: white;
				margin: 10px 0;
				box-sizing: border-box;
				width: 100%;
				height: 65px;
				padding-top: 10px;
				padding-left: 10px;
			}
			.page_withdraw #btn-income-record{
				color: white;
				font-size: 12px;
				float: right;
				/* margin-right: 10px; */
			}
			.page_withdraw .arrival-bank-card{
				float: left;
			}
			.page_withdraw .arrival-bank-card-info{
				float: left;
				margin-left: 10px;
			}
			.page_withdraw .arrival-bank-card-info-bottom{
				margin-top: 5px;
			}
			.page_withdraw .income-limit{
				background-color: white;
				margin: 10px 0;
				width: 100%;
				height: 120px;
				padding-top: 10px;
				box-sizing: border-box;
				padding-left: 10px;
			}
			.page_withdraw .income-limit-content{
				border-bottom: 1px solid rgba(200,200,200,0.2);
			}
			.page_withdraw .income-limit-content .ilcd{
				font-size: 25px;
				width: 100%;
				height: 45px;
				line-height: 45px;
			}
			.page_withdraw .income-limit-content .ilcdl{
				font-weight: bold;
				float: left;
			}
			.page_withdraw .income-limit-content .ilcdi{
				float: left; 
				width: 250px;
				font-size: 23px; 
				border: none;
				outline: none; 
				margin-top: 6px;
			}
			.page_withdraw .income-limit-tips{
				width: 100%; 
				position: relative;
				height: 32px;
				line-height: 32px;
			}
			.page_withdraw .income-limit-tips p{
				position: absolute;
				font-size: 13px;
			}
			.page_withdraw .income-limit-tips button{
				border: none;
				background-color: white; 
				color: darkgoldenrod;
				position: absolute;
				top: 50%;
				margin-top: -14.5px;
				right: 10px; 
				font-size: 13px;
			}
			.page_withdraw .pay-ps{
				padding-left: 10px;
				background-color: white;
			}
			.page_withdraw .pay-ps-in{
				float: left; 
				border: none;
				box-sizing: border-box;
				padding-left: 10px; 
				background-color: white;
			}
			.page_withdraw #presentation{
				box-sizing: border-box;
				padding: 10px;
			}
			.page_withdraw #comfirmReturn{
				margin-top: 34px;
			}
			.page_withdraw .weui-btn:after {
				border: none;
			}
		</style>
	</head>
	
		<div class="page_withdraw">
			<div class="card-content">
				<div class="arrival-bank-card">
					<span>到账银行卡</span>
				</div>
				<div class="arrival-bank-card-info">
					<span class="arrival-bank-card-info-top">{{cardform.cardname}}</span><br>
					<p class="arrival-bank-card-info-bottom">尾号{{cardform.cardid}}储蓄卡</p>
				</div>
			</div>
			<div class="income-limit">
				<div class="income-limit-content">
					<p>提现金额</p>
					<div class=" ilcd">
						<label class="ilcdl">￥</label>
						<input class="ilcdi" type="text" placeholder="输入金额" v-model="withdrawform.coin">
					</div>
				</div>
				<div class="income-limit-tips">
					<p style="">可提现金额{{couldincome}}元</p>
					<button type="button" class="weui-btn weui-btn_mini">全部提现</button>
				</div>
			</div>
			<p class="pay-ps">支付密码</p>
			<input class="weui-input pay-ps-in" type="password" placeholder="输入6位支付密码" v-model="withdrawform.pay_password">
			<button id="comfirmReturn" type="button" class="weui-btn weui-btn_primary">确认提现</button>
			<div id="presentation">
				<p>提现说明：</p>
				<div id="presentation-content">
					<p>1、提现于次日18点前到账，周末照常到账，每天收益提现不能超过1笔；</p>
					<p>2、法定节假日提现，统一在节后的第一个工作日18点前到账；</p>
					<p>3、提现金额不小于100元；</p>
				</div>
				<p>手续费说明：</p>
				<p>1、单笔收取手续费2元</p>
			</div>
		</div>

		<script>
			(function(){
				new Vue({
					el:'.page_withdraw',
					data: {
						cardform: {
							cardname:'',
							cardid:''
						},
						couldincome:'0.00',
						withdrawform:{
							coin:'',
							pay_password:''
						}
						
					},
					mounted() {
						this.getBankCard();
						this.getCouldIncome();
					},
					methods:{
						checkData(e){
						},
						getBankCard(){
							let debitCardUrl = '/mobile/BankCard/debitCard';
							$.showLoading();
							let self = this;
							$.post({
							    url: debitCardUrl,
							    success(res) {
							    	console.log(res)
							    	let curdata = res.data;
							    	console.log(curdata[0]);
							    	self.cardform.cardname = curdata[0].bank_name;

							    	let no = curdata[0].card_no;
							    	self.cardform.cardid = no.slice(no.length-4);
							    	$.hideLoading();
							    }
							});
						},
						getCouldIncome(){
							let userUrl = '/mobile/user/user';
							$.showLoading();
							let self = this;
							$.post({
							    url: userUrl,
							    success(res) {
							    	console.log(res)
							    	let curdata = res.data;
							    	self.couldincome = curdata.balance;
							    	$.hideLoading();
							    }
							});
						},
						incomeTo(){

						},
					}
				});
			})();
			
		</script>
	</body>

</html>
