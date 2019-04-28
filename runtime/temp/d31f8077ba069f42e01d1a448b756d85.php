<?php /*a:1:{s:67:"/cs/docker/nginx/html/csh5/application/mobile/view/user/friend.html";i:1546922828;}*/ ?>

		<style>
			.page_friend .common-img{
				width: 100%;
			}
			.page_friend .btn-back{
				position: fixed;
				bottom: 0px;
				margin-bottom: 0;
				z-index: 1000;
			}
			.page_friend .weui-cells{
				margin-top: 0;
				margin-bottom: 46px;
			}
			.page_friend .friend-img{
				width:50px;
				height:50px; 
				float: left;
				margin-left: 20px;
				margin-right: 20px;
				border-radius: 50%;
			}
			.page_friend .grey{
				color: grey;
			}
			.page_friend .orange{
				color: orange;
			}
		</style>

		<div class="page_friend">
			<img class="common-img" src="/static/imgs/ad1.png">
			<div class="cbtn">
				<div class="weui-cells" style="">
			        <div class="weui-cell weui-cell_access" v-for="item in buddyList" @click="levelGoForth(item)">
			            <div class="weui-cell__hd">
			            	<img src="/static/imgs/ad1.png" class="friend-img" style="" />
			            </div>
			            <div class="weui-cell__bd">
			                <p>{{item.phone}}（{{item.role==null?'无':item.role.role_name}}）</p>
			                <p class="grey"> 团队人数<span style="color: orange;">{{item.team_count}}</span>人&nbsp;&nbsp;&nbsp;&nbsp;
			                	累计收益<span class="orange"> ￥{{item.income_amount==null?0:item.income_amount}}</span>
							</p>
			            </div><br>
			            <div class="weui-cell__ft">
			            </div>
			        </div>
	        	</div>
				<button type="button" class="weui-btn weui-btn_primary btn-back" @click="levelBack()">返回上一层级</button>
			</div>
		</div>

		<script type="text/javascript">
			(function () {
				let uid = '<?php echo $id; ?>'
				new Vue({
					el: '.page_friend',
					data: {
						form:{
							id: '<?php echo $id; ?>'
						},
						buddyList: [],
						preId: [],
						level: 1,
						page:1,
						isloading:false
					},
					mounted(){
						this.getFriendData();
					},
					methods: {
						levelBack(){

			 				//点击返回获取上一层级数据
							if (this.level <= 1) {
							$.alert('当前为顶层!');
							return;
							}
							this.form.id = this.preId[this.level - 2];
							this.preId.pop();
							this.getFriendData();
							this.level--;
							console.log(this.level);
						},
						levelGoForth(it){

							//触发子窗口传递下列数据变更当前页面好友列表详情
							this.preId[this.preId.length] = this.form.id;
							this.form.id = it.uid;
							this.getFriendData();
							this.level++;
							console.log(this.level);
						},
						getFriendData(){

							// 防止误操作(多次点击)
							if (this.isloading === true) {
								return;
							}
							this.isloading = true;

							let friendsUrl = '/mobile/User/friends';
							$.showLoading();
							let self = this;
							
							$.post({
							    url: friendsUrl,
							    data: this.form,
							    success(res) {

									self.isloading = false;

							    	console.log("好友列表:")
							    	console.log(res)
							    	let curdata = res.data.data;
							    	
							    	$.hideLoading();
							    	if (curdata == '') {
										self.level--;
										$.alert('已到最后层级!');
										return;
									}
									self.buddyList = curdata;
							    }
							});
						}
					}
				});
			})();
		</script>
	</body>

</html>
