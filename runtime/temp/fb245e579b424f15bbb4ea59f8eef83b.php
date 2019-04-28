<?php /*a:1:{s:74:"/cs/docker/nginx/html/csh5/application/mobile/view/mailbox/addMailbox.html";i:1545735395;}*/ ?>

		<style>

		</style>
	</head>

		<div class="page_addMailbox" style="padding: 0 10px">
			<div class="weui-cells weui-cells_form itemul">
			  <div class="weui-cell">
			    <div class="weui-cell__hd"><label class="weui-label">账号</label></div>
			    <div class="weui-cell__bd">
			      <input class="weui-input" type="text" placeholder="请输入邮箱账户" v-model="form.username">
			    </div>
			  </div>
			  <div class="weui-cell">
			    <div class="weui-cell__hd"><label class="weui-label">密码</label></div>
			    <div class="weui-cell__bd">
			      <input class="weui-input" type="text" placeholder="请输入邮箱授权码或密码" v-model='form.password'>
			    </div>
			  </div>
			  <button type="button" class="weui-btn weui-btn_primary" style="margin-top: 10px;" @click="addmail()">添加</button>
			  <a style="font-size: 15px;float: left;margin-top: 12px;">手动设置</a>
			</div>
		</div>

		<script type="text/javascript">
			(function(){
				// vue dom 绑定与监听
				new Vue({
					el:'.page_addMailbox .itemul',
					data: {
						form:{
							username:null,
							password:null,
							mailbox_id:page_id,
						}
					},
					mounted () {
						console.log(this.form)
					},
					methods: {
						addmail(){
							let Mailmyturl = '/mobile/Mailbox/add';
							$.showLoading();
							let posdata = this.form;
							posdata.protocol = "pop";
							$.post({
							    url: Mailmyturl,
							    data:posdata,
							    success(res) {
							    	$.alert(alertmsg(res));
							    	$.hideLoading();
							    }
							});
						}
					},
				});
			})();
		</script>
	</body>

</html>