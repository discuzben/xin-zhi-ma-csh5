<?php /*a:1:{s:73:"/cs/docker/nginx/html/csh5/application/mobile/view/repayment/openurl.html";i:1545903550;}*/ ?>

<iframe id="iframe_bindcard" width="100%" height="100%"></iframe>
<script>
		$(function(){
			//
			var screenheight = document.documentElement.clientHeight;
			$('#iframe1').height(screenheight);
			
			var bcuid='<?php echo htmlentities($bcuid); ?>';
			console.log(bcuid);
			$.post('http://pay2.xinzhima.cn/index/leshuapay/bindCardUrl',{'bcuid':bcuid},function(data){
				console.log(JSON.stringify(data));
				$('#iframe_bindcard').attr('srcdoc',data.url);
			})
			
		})
		
	</script>

