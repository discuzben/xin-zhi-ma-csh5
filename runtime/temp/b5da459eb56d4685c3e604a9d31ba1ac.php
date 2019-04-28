<?php /*a:1:{s:72:"/cs/docker/nginx/html/csh5/application/mobile/view/home/creditScore.html";i:1545374700;}*/ ?>

		<style>
			.page_creditScore{
				position: relative;
			}
			.page_creditScore img{
				width: 100%;
				height: 100%;
				position: fixed;
			}
			.page_creditScore p{
				width: 100%;
				height: 30px;
				position: absolute;
				text-align: center;
				color: darkgoldenrod;
			}
			.page_creditScore .p1{
				top: 360px;
			}
			.page_creditScore .p2{
				top: 390px;
			}
			.page_creditScore .p3{
				top: 420px;
			}
			.page_creditScore .p4{
				top: 450px;
			}
			.page_creditScore .p5{
				top: 480px;
			}
			.page_creditScore .p6{
				top: 510px;
			}
			.page_creditScore .p7{
				top: 540px;
			}
			.page_creditScore .p8{
				top: 570px;
			}
			.page_creditScore .p9{
				top: 600px;
			}
			.page_creditScore .p10{
				top: 630px;
			}
			.page_creditScore .p11{
				top: 660px;
			}
			.page_creditScore #btn-my-score{
				color: white;
				font-size: 14px;
				float: right;
				margin-right: 10px;
			}
			.page_creditScore #mb{
				line-height: 44px;
			}
			.page_creditScore .scorenum{
				position: absolute;
				width: 100%;
				text-align: center;
				top: 70px;
				font-size: 40px;
				color: darkgoldenrod;
			}
			.page_creditScore .scorenum-tip{
				font-size: 16px;
				position: absolute;
				color: darkgoldenrod;
				text-align: center;
				top: 130px;
				width: 100%;
			}
			.page_creditScore .anim{
				position: relative;
				width: 240px;
				height: 240px;
				border-radius: 50%;
				/* border: 1px solid #f00; */
				box-sizing: border-box;
				margin: 50px auto 0;
			} 
			.page_creditScore .anim li{
				position: absolute;
				z-index: 2;
				left: 50%;
				top: 0;
				width: 40px;
				height: 40px;
				margin: -20px 0 0 -20px;
				/* border: 1px solid #f00; */
				/*border-radius: 50%;*/
				box-sizing: border-box;
				list-style: none;
				transform-origin: 20px 140px;
				text-align: center;
				line-height: 40px;
			}
			.page_creditScore .iconwater{
				color: rgba(0,0,0,.3);
			}
		</style>

		<div class="page_creditScore">
			<ul class="anim" id="box">
			</ul>
			<span class="scorenum">800</span>
			<span class="scorenum-tip">评估时间：2018-11-01</span>
			<p class="p1">信用分</p>
			<p class="p2">是芯芝麻打造自有核心的信用体系</p>
			<p class="p3">以收益和利润为参照</p>
			<p class="p4">依托信用分 可以展开无限的想象</p>
			<p class="p5">信贷</p>
			<p class="p6">分期</p>
			<p class="p7">免抵押</p>
			<p class="p8">信用分抵押</p>
			<p class="p9">信用分置换</p>
			<p class="p10">......</p>
			<p class="p11">在芯芝麻，高分值的你就是王！</p>
		</div>

		<script type="text/javascript">
			
			//信用分动画编写
			//绘制画面
			var liSize = 20,li = '';
			for(var i=liSize/2+2; i<liSize; i++) {
				li += '<li style="transform: rotate('+360/liSize*i+'deg)"><span class="font_family icon-water-drop"></li>';
			}
			for(var i=0; i<liSize/2-1; i++) {
				li += '<li style="transform: rotate('+360/liSize*i+'deg)"><span class="font_family icon-water-drop"></li>';
			}
			var cc = document.getElementsByClassName('anim')[0];
			cc.innerHTML = li;
			
			//定时器控制动画
			var int=self.setInterval("clock()",100),liindex=0,scorenum = 800;
			function clock(){
			   cc.children[liindex].querySelector("span").style.color = 'white';
			   liindex++;
			   var sc = Math.ceil(scorenum/(liSize-3)*liindex);
			   document.getElementsByClassName('scorenum')[0].innerHTML = sc;
			   if(liindex == 17){
				   clearInterval(int);
			   }
			}
		</script>
