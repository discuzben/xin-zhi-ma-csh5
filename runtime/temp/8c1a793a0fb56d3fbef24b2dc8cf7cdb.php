<?php /*a:1:{s:71:"/cs/docker/nginx/html/csh5/application/mobile/view/mailbox/mailbox.html";i:1545659900;}*/ ?>

		<style>
			.page_mailbox .else-account{
				list-style: none;
				-webkit-padding-start: 10px;
				margin-top: 10px;
			}
			.page_mailbox .else-account li{
				height: 50px;
				width: 100px;
				float: left;				
				text-align: center;
				margin-right: 5px;
			}
			.page_mailbox .else-account li a{
				height: 50px;
				width: 100px;
				float: left;				
				text-align: center;
			}
			.page_mailbox .else-account .iconcircle{
				
			}
			.page_mailbox .else-account li span{
				margin-left: 25px;
				font-size: 25px;
				color: white;
				width: 50px;
				height: 50px;
				float: left;
				border-radius: 50%;
				position: relative;
			}
			.page_mailbox .else-account li:nth-child(1) span{
				background-color: lightseagreen;
				border-radius: 50%;
			}
			.page_mailbox .else-account li:nth-child(2) span{
				background-color: mediumslateblue;
			}
			.page_mailbox .else-account li p{
				color: #000000;
				float: left;
			}
			.page_mailbox .icon-duanxin::before {
				position: absolute;
				left: 50%;
				margin-left: -27.75px;
				top: 50%;
				margin-top: -20px;
			}
			.page_mailbox .icon-shoudong::before {
				position: absolute;
				left: 50%;
				margin-left: -12.5px;
				top: 50%;
				margin-top: -20px;
			}
			.page_mailbox .weui-cell__bd p{
				color: grey;
			}
			.page_mailbox .weui-cells{
				background-color: rgb(239,234,231);
			}
		</style>

		<div class="page_mailbox" id="page_mailbox">
			<div class="weui-cells">
			    <a class="weui-cell weui-cell_access" href="javascript:;" @click = "openselectMail()">
			    	<div class="weui-cell__bd">
			        	<span>添加邮箱中的账单</span>
						<p style="font-size: 13px;">添加账单收取邮箱，收取全部信用卡账单</p>
			    	</div>
			    	<div class="weui-cell__ft">
			    	</div>
			    </a>
			    <a class="weui-cell weui-cell_access" href="javascript:;" v-for = 'item in usernameList' @click = "openparse(item)">
			    	<div class="weui-cell__bd">
			      		<span>{{item.username}}</span>
			    	</div>
			    	<div class="weui-cell__ft">
			    	</div>
			    </a>
			    <a class="weui-cell weui-cell_access" href="javascript:;">
			    	<div class="weui-cell__bd">
			      		<span>其他方式查询账单</span>
						<p style="font-size: 13px;">没有邮件？试试这些方式</p>
			    	</div>
			    </a>
			</div>
			<ul class ="else-account">
				<li>
					<a class="mui-pull-left mepage-help touch-action">
						<div class="iconcircle">
							<span class="font_family icon-duanxin"></span>
						</div>
						<p>短信导入账单</p>
					</a>
				</li>
				<li>
					<a class="mui-pull-left mepage-help touch-action">
						<span class="font_family icon-shoudong"></span>
						<p>手动输入账单</p>
					</a>
				</li>
			</ul>
		</div>

		<script>
			// //请求体处理
			// function da(){
			// 	var da ={};
			// 	var token=localStorage.logintoken;
			// 	//保存本地的数据
			// 	var signkey=localStorage.signkey;
			// 	var signcode=sign(da,signkey);
									
			// 	da['sign']=signcode;
			// 	return da;
			// }
			// //数据处理
			// function dealdata(d){
			// 	let s;
			// 	if(d.length == 0 && !d.indexOf('@')){
			// 		return;
			// 	}
			// 	s = d.slice(0,1)+'******'+d.slice(d.indexOf('@')-1,d.length);
			// 	return s;
			// }
			(function(){
				"use strict"
				new Vue({
					el: '#page_mailbox',
						data: {
							usernameList: []
						},
						mounted() {
							let Mailmyturl = '/mobile/Mailbox/my';
							$.showLoading();
							let self = this;
							$.ajax({
							    url: Mailmyturl,
							    success(res) {
							    	let curdata = res.data;
							    	if (res.code == '0000') {
							    		$.hideLoading();
							    		self.usernameList = curdata;
							    		
							    		console.log(self.usernameList)
							    	}else{
							    		$.alert(alertmsg(res.code),"attention");
							    	}
							    }
							});

						},
						methods:{
							openselectMail(){
								$.showLoading();
					            var winname="win_smb"
				                CreateKeeWindow.createKeeWindow("keewindow",winname);
				                CreateKeeWindow.setTitle(winname,"选择邮箱");
				                $("#"+winname+"_contents").load("/mobile/mailbox/selectMailboxView",{},function () {
				                    $.hideLoading();
				                })
				                CreateKeeWindow.showKeeWindow(winname);
							},
							openparse (it){
								page_id = it.id;
								$.showLoading();
					            var winname="win_gmb"
				                CreateKeeWindow.createKeeWindow("keewindow",winname);
				                CreateKeeWindow.setTitle(winname,"获取邮件");
				                $("#"+winname+"_contents").load("/mobile/mailbox/getMailboxView",{},function () {
				                    $.hideLoading();
				                })
				                CreateKeeWindow.showKeeWindow(winname);
							}
						}
				})
			})();
		</script>