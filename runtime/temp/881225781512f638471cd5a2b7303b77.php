<?php /*a:1:{s:77:"/cs/docker/nginx/html/csh5/application/mobile/view/mailbox/selectMailbox.html";i:1545735395;}*/ ?>

		<style>
			.page_selcetMailbox .itemul-cell{
				height: 80px;
				border-bottom: 1px solid rgba(200,200,200,0.2);
				/* text-align: center; */
			}
			.page_selcetMailbox .itemul-cell a{
				width: 100%;
				height: 80px;
				position: relative;
				float: left;
			}
			.page_selcetMailbox .itemul-cell img{
				position: absolute;
				top: 0;
				left: 0;
				bottom: 0;
				right: 0;
				margin: auto;
			}
			.page_selcetMailbox .cell-p{
				position: relative;
				float: left;
				width: 100%;
				text-align: center;
				margin-top: 50px;
			}
			.page_selcetMailbox .cell-content{
				text-align: center;
				position: relative;
				top:50%;
				margin-top:-21px;
			}
		</style>
	</head>

		<div class="page_selcetMailbox">
			<ul class="itemul">
				<li class="itemul-cell" v-for = 'item in mailList' @click="openaddMail(item)">
					<a class="touch-action" v-if="item.name == 163">
						<img  src="/static/imgs/mail/163logo.gif" >
					</a>
					<a class="touch-action" v-else-if="item.name == 126">
						<img src="/static/imgs/mail/126logo.gif" >
					</a>
					<a class="touch-action" v-else-if="item.name === 'qq'">
						<img src="/static/imgs/mail/qqlogo.gif" >
						<p class="cell-p">(需要获取QQ邮箱授权码)</p>
					</a>
					<a class="touch-action" v-else-if="item.name == 189">
						<img src="/static/imgs/mail/189logo.jpg" >
					</a>
					<a class="touch-action" v-else-if="item.name == 'sina'">
						<img src="/static/imgs/mail/sinalogo.jpg" >
					</a>
					<a class="touch-action" v-else>
						<div class="cell-content">
							<span>其他邮箱</span>
							<p>(覆盖60万种)</p>											
						</div>
					</a>
				</li>
			</ul>
		</div>

		<script type="text/javascript">
			(function() {
				new Vue({
					el: '.page_selcetMailbox .itemul',
					data: {
						mailList: []
					},
					mounted () {
						let Mailmyturl = '/mobile/Mailbox/support';
						$.showLoading();
						let self = this;
						$.ajax({
						    url: Mailmyturl,
						    success(res) {
						    	let curdata = res.data;
						    	if (res.code == '0000') {
						    		$.hideLoading();
						    		self.mailList = curdata;
						    	}else{
						    		$.alert(alertmsg(res),"attention");
						    	}
						    }
						});
					},
					methods: {
						openaddMail(it){
							page_id = it.id;
							$.showLoading();
				            var winname="win_amb"
			                CreateKeeWindow.createKeeWindow("keewindow",winname);
			                CreateKeeWindow.setTitle(winname,"添加邮箱");
			                $("#"+winname+"_contents").load("/mobile/mailbox/addMailboxView",{},function () {
			                    $.hideLoading();
			                });
			                CreateKeeWindow.showKeeWindow(winname);
						},
					}
				})
			})()
		</script>
	</body>

</html>