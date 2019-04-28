<?php /*a:1:{s:69:"/cs/docker/nginx/html/csh5/application/mobile/view/home/feedback.html";i:1545374700;}*/ ?>

<style type="text/css">
			
.page_feedback body {
	background-color: #EFEFF4;
}
.page_feedback input,
.page_feedback textarea {
	border: none !important;
}
.page_feedback textarea {
	margin-bottom: 0 !important;
	padding-bottom: 0 !important;
}
.page_feedback p {
}

.page_feedback input::-webkit-input-placeholder,textarea::-webkit-input-placeholder{
	font-size: 14px;
}

.page_feedback .hidden {
	display: none;
}
.page_feedback .image-list {
	width: 100%;
	/*height: 45px;
	line-height: 45px;*/
	background-size: cover;
	padding: 10px 10px;
	overflow: hidden;
	background: rgb(255,255,255);
}
.page_feedback .icon-jiahao {
	font-size: 40px;
	color: rgba(0,0,0,.2);
	border: 1px solid rgba(0,0,0,.2);
	border-radius: 5px;
}
.page_feedback .image-item {
	width: 65px;
	height: 65px;
	/*background-image: url(../images/iconfont-tianjia.png);*/
	background-size: 100% 100%;
	display: inline-block;
	position: relative;
	border-radius: 5px;
	margin-right: 10px;
	margin-bottom: 10px;
	border: solid 1px #e8e8e8;
	vertical-align: top;
}
.page_feedback .image-item .file {
	position: absolute;
	left: 0px;
	top: 0px;
	width: 100%;
	height: 100%;
	opacity: 0;
	cursor: pointer;
	z-index: 0;
}
.page_feedback .image-item.space {
	border: none;
}
.page_feedback .image-item .image-close {
	position: absolute;
	display: inline-block;
	right: -6px;
	top: -6px;
	width: 20px;
	height: 20px;
	text-align: center;
	line-height: 20px;
	border-radius: 12px;
	background-color: #FF5053;
	color: #f3f3f3;
	border: solid 1px #FF5053;
	font-size: 9px;
	font-weight: 200;
	z-index: 1;
}
.page_feedback .image-item .image-up{
	height: 65px;
	width: 65px;
	border-radius: 10px;
	line-height: 65px;
	border: 1px solid #ccc;
	color: #ccc; 
	display: inline-block;
	text-align: center;
}
.page_feedback .image-item .image-up:after{
	font-family: "微软雅黑";
	content: '+';
	font-size: 60px;
}
.page_feedback .image-item.space .image-close {
	display: none;
}
.page_feedback .mui-inline{
	vertical-align: center;
	font-size: 14px;
	color: #8f8f94;
}
.page_feedback .icon-star{
	color: #B5B5B5;
	font-size: 22px;
}
.page_feedback .icon-star-filled{
	color: #FFB400;
	font-size: 22px;
} 
.page_feedback .mui-popover {
	height: 180px;
}
.page_feedback .stream{
	display: none;
}
.page_feedback .mui-plus-stream .stream{
	display: block;
}
.mui-content-padded {
    /*margin: 10px;*/
}

.page_feedback .mui-inline {
    display: inline-block;

    vertical-align: top;
}
.page_feedback .mui-inline {
    padding: 8px 0;
}
.page_feedback .mui-pull-right {
    float: right;
}
.page_feedback .mui-popover {
    position: absolute;
    z-index: 999;

    display: none;

    width: 280px;

    -webkit-transition: opacity .3s;
    transition: opacity .3s;
    -webkit-transition-property: opacity;
    transition-property: opacity;
    -webkit-transform: none;
    transform: none;

    opacity: 0;
    border-radius: 7px;
    background-color: #f7f7f7;
    -webkit-box-shadow: 0 0 15px rgba(0, 0, 0, .1);
    box-shadow: 0 0 15px rgba(0, 0, 0, .1);
}
.page_feedback .mui-popover .mui-popover-arrow {
    position: absolute;
    z-index: 1000;
    top: -25px;
    left: 0;

    overflow: hidden;

    width: 26px;
    height: 26px;
}
.page_feedback .mui-popover .mui-scroll-wrapper {
    margin: 7px 0;

    border-radius: 7px;
    background-clip: padding-box;
}
.page_feedback .mui-popover .mui-scroll .mui-table-view {
    max-height: none;
}

.page_feedback .mui-popover .mui-table-view {
    overflow: auto;

    max-height: 300px;
    margin-bottom: 0;

    border-radius: 7px;
    background-color: #f7f7f7;
    background-image: none;

    -webkit-overflow-scrolling: touch;
}

.page_feedback .mui-popover .mui-table-view:before, .mui-popover .mui-table-view:after {
    height: 0;
}

.page_feedback .mui-popover .mui-table-view .mui-table-view-cell:first-child,
.page_feedback .mui-popover .mui-table-view .mui-table-view-cell:first-child > a:not(.mui-btn) {
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
}

.page_feedback .mui-popover .mui-table-view .mui-table-view-cell:last-child,
.page_feedback .mui-popover .mui-table-view .mui-table-view-cell:last-child > a:not(.mui-btn) {
    border-bottom-right-radius: 12px;
    border-bottom-left-radius: 12px;
}
.page_feedback .question{
	width: 100%;
	height: 80px;
	outline: none;
}
.page_feedback #contact{
	width: 100%;
	height: 30px;
	outline: none;
}
.page_feedback #in:active{
	color: lightgrey;
}
.weui-picker-container{
	z-index: 2002;
}
.page_feedback .touch-action{
	touch-action:none;
}
</style>
<div class="page_feedback">
	<div class="mui-content-padded">
		<div class="mui-inline">问题和意见</div>
		<a class="mui-pull-right mui-inline"  style="margin-right: 10px" id="in">
			快捷输入
			<span class="font_family icon-xiangxiajiantou"></span>
		</a>
	</div>

	<div class="mui-input-row">
		<textarea id='question' class="mui-input-clear question" placeholder="请详细描述你的问题和意见..."></textarea>
	</div>
	<div class="weui-cells weui-cells_form">
	  <div class="weui-cell">
	    <div class="weui-cell__bd">
	      <div class="weui-uploader">
	        <div class="weui-uploader__hd" style="font-size: 14px;color: #8f8f94;">
	          <p class="weui-uploader__title" >图片上传</p>
	          <div class="weui-uploader__info">0/2</div>
	        </div>
	        <div class="weui-uploader__bd">
	          <ul class="weui-uploader__files" id="image-list">
	           <!--  <li class="weui-uploader__file weui-uploader__file_status" style="background-image:url(./images/pic_160.png)">
	              <div class="weui-uploader__file-content">
	                <i class="weui-icon-warn"></i>
	              </div>
	            </li>
	            <li class="weui-uploader__file weui-uploader__file_status" style="background-image:url(./images/pic_160.png)">
	              <div class="weui-uploader__file-content">50%</div>
	            </li> -->
	          </ul>
	          <div class="weui-uploader__input-box">
	            <input id="uploaderInput" class="weui-uploader__input" type="file" accept="image/*" multiple="">
	          </div>
	        </div>
	      </div>
	    </div>
	  </div>
	</div>
	<!-- <div class="mui-content-padded">
		<p class="mui-inline">图片(选填,提供问题截图,总大小10M以下)</p>
	</div> -->
	<!-- <div id='image-list' class="image-list"></div> -->
	<p class="mui-inline">QQ/邮箱</p>
	<div class="mui-input-row">
		<input id='contact' type="text" class="mui-input-clear contact" placeholder="(选填,方便我们联系你 )" />
	</div>
	<div class="mui-content-padded" style="height: 57px;line-height: 57px">
		<div class="mui-inline" style="float: left;padding: 0;">应用评分</div>
		<div class="icons touch-action" style="margin-left: 6px;float: left;">
			<i data-index="1" class="font_family icon-star"></i>
			<i data-index="2" class="font_family icon-star"></i>
			<i data-index="3" class="font_family icon-star"></i>
			<i data-index="4" class="font_family icon-star"></i>
			<i data-index="5" class="font_family icon-star"></i>
		</div>
	</div>
	<a href="javascript:;" class="weui-btn" style="background-color: #168BD8; width: 100%;" id="submit">发送</a>
	<br />
</div>
<script type="text/javascript">
	$(function() {
	    FastClick.attach(document.body);
	});
  	$('.page_feedback #in').select({
		title:"快捷输入",
		items:["界面显示错乱","启动缓慢，卡出翔了","偶发性崩溃","UI无法直视，丑哭了"],
		onChange: function(d) {
			$('#question').html(d.values);
    	}
	});
	(function() {
	var index = 1;
	var size = null;
	var imageIndexIdNum = 0;
	var starIndex = 0;
	var feedback = {
		question: document.getElementById('question'), 
		contact: document.getElementById('contact'), 
		imageList: document.getElementById('image-list'),
		submitBtn: document.getElementById('submit')
	};
	var url = 'https://api2.xinzhima.cn/feedback';
	feedback.files = [];
	feedback.uploader = null;  

	// feedback.deviceInfo = null; 
	// 	//设备信息，无需修改
	// 	feedback.deviceInfo = {
	// 		appid: plus.runtime.appid, 
	// 		imei: plus.device.imei, //设备标识
	// 		images: feedback.files, //图片文件
	// 		p: mui.os.android ? 'a' : 'i', //平台类型，i表示iOS平台，a表示Android平台。
	// 		md: plus.device.model, //设备型号
	// 		app_version: plus.runtime.version,
	// 		plus_version: plus.runtime.innerVersion, //基座版本号
	// 		os:  mui.os.version,
	// 		net: ''+plus.networkinfo.getCurrentType()
	// 	}

	/**
	 *提交成功之后，恢复表单项 
	 */
	feedback.clearForm = function() {
		feedback.question.value = '';
		feedback.contact.value = '';
		feedback.imageList.innerHTML = '';
		feedback.newPlaceholder();
		feedback.files = [];
		index = 0;
		size = 0;
		imageIndexIdNum = 0;
		starIndex = 0;
		//清除所有星标
		mui('.icons i').each(function (index,element) {
			if (element.classList.contains('mui-icon-star-filled')) {
				element.classList.add('mui-icon-star')
	  			element.classList.remove('mui-icon-star-filled')
			}
		})
	};
	feedback.getFileInputArray = function() {
		return [].slice.call(feedback.imageList.querySelectorAll('.file'));
	};
	feedback.addFile = function(path) {
		feedback.files.push({name:"images"+index,path:path,id:"img-"+index});
		index++;
	};
// 	/**
// 	 * 初始化图片域占位
// 	 */
	feedback.newPlaceholder = function() {
		var fileInputArray = feedback.getFileInputArray();
		if (fileInputArray &&
			fileInputArray.length > 0 &&
			fileInputArray[fileInputArray.length - 1].parentNode.classList.contains('space')) {
			return;
		};
		imageIndexIdNum++;
	};
	feedback.newPlaceholder();
	feedback.submitBtn.addEventListener('click', function(event) {
		if (feedback.question.value == '' ||
			(feedback.contact.value != '' &&
				feedback.contact.value.search(/^(\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+)|([1-9]\d{4,9})$/) != 0)) {
			return $.alert('信息填写不符合规范');
		}
		if (feedback.question.value.length > 200 || feedback.contact.value.length > 200) {
			return $.alert('信息超长,请重新填写~')
		}
		//判断网络连接
		if(plus.networkinfo.getCurrentType()==plus.networkinfo.CONNECTION_NONE){
			return $.alert("连接网络失败，请稍后再试");
		}
		feedback.send($.extend({}, feedback.deviceInfo, {
			content: feedback.question.value,
			contact: feedback.contact.value,
			images: feedback.files,
			score:''+starIndex
		})) 
	}, false)
	feedback.send = function(content) {
		//添加上传数据
		$.each(content, function(index, element) {
			if (index !== 'images') {
				console.log("addData:"+index+","+element);
//				console.log(index);
				feedback.uploader.addData(index, element)
			} 
		});
		//添加上传文件
		$.each(feedback.files, function(index, element) {
			var f = feedback.files[index];
			console.log("addFile:"+JSON.stringify(f));
			feedback.uploader.addFile(f.path, {
				key: f.name
			});
		});
		//开始上传任务
		feedback.uploader.start();
		$.alert("感谢反馈，点击确定关闭",function () {
			feedback.clearForm();
			$.back();
		});
	};
	
	 //应用评分
	 $('.page_feedback .icons').on('click','i',function(){
	  	var index = parseInt(this.getAttribute("data-index"));
	  	var parent = this.parentNode;
	  	var children = parent.children;
	  	if(this.classList.contains("mui-icon-star")){
	  		for(var i=0;i<index;i++){
  				children[i].classList.remove('mui-icon-star');
  				children[i].classList.add('icon-star-filled');
	  		}
	  	}else{
	  		for (var i = index; i < 5; i++) {
	  			children[i].classList.add('mui-icon-star')
	  			children[i].classList.remove('icon-star-filled')
	  		}
	  	}
	  	starIndex = index;
  });
})();
</script>