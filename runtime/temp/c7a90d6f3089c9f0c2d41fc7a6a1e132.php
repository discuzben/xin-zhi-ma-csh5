<?php /*a:1:{s:69:"/cs/docker/nginx/html/csh5/application/mobile/view/user/myPolicy.html";i:1547100935;}*/ ?>

		<style>
		.page_myPolicy{
			position: relative;
		}
		.page_myPolicy #bg{
			width: 100%;
			height: 100%;
			position: fixed;
		}
		</style>
	</head>

		<div class="page_myPolicy">
			<img id="bg" src="/static/imgs/guaranty.jpg">
			<!-- <div class="mui-content-padded"> -->
				<!-- <p>保单二维码</p> -->
				<!-- <p> -->
					<img id="image_touch" style="position: absolute;left: 50%;" src="/static/imgs/mywarranty.jpg" data-preview-src="" data-preview-group="1" />
				<!-- </p> -->
				<!-- <p style="margin-top: 350px;">图片全屏后，双击或双指缩放均可对图片进行放大、缩小操作，左右滑动可查看同组其它图片，点击保存到手机相册中，使用微信点开扫码，链接到我的保单点击会关闭预览, -->
				<!-- </p> -->
			<!-- </div> -->
		</div>
		
		<script type="text/javascript">
			(function(){
				function set(obj,t,w,h){
					let trate = $(document.body).height()/h*t;
					let hrete = $(document.body).height()/h*300;
					let wrate = $(document.body).width()/w*300;
				 	obj.css({'top':trate+'px','width':hrete+'px','height':hrete+'px','margin-left':-hrete/2+'px'});
				}
				set($('.page_myPolicy #image_touch'),80,375,812);
			})();
			// mui.previewImage();
			// function saveImage(){
			// 	var imgUrl = document.getElementById("image_touch").src;
			// 	console.log(imgUrl);
			// 	plus.gallery.save(imgUrl, function(){
			// 		mui.toast('保存成功');
			// 	}, function(e){
			// 		mui.toast('保存失败: '+JSON.stringify(e));
			// 	});
			// }

		</script>
	</body>

</html>
