<?php /*a:1:{s:72:"/cs/docker/nginx/html/csh5/application/mobile/view/index/infoDetail.html";i:1546919452;}*/ ?>
		
		<style type="text/css">
			.page_infoDetail .bar-nav {
				background-color: #4a82d1;
				-webkit-box-shadow: none;
				box-shadow: none;
			}
			.page_infoDetail .bar-nav a,
			.page_infoDetail .bar .btn-link {
				color: white;
			}
			.page_infoDetail .kr-title {
				color: white;
			}

			.page_infoDetail .kr-article .kr-slider a {
				height: 180px;
				background-position: center;
				background-size: 100%;
				background-repeat: no-repeat;
			}

			/*无需重复显示大图*/
			.page_infoDetail #kr-article-article img.fr-fin.fr-dib.fr-tag{
				display: none;
			}

			.page_infoDetail #kr-article-article p img:first-child{
				display: none;
			}

			.page_infoDetail .kr-article .kr-slider a .slider-title {
				background: transparent;
				color: white;
				opacity: 1;
				bottom: 15px;
				font-size: 20px;
				font-weight: 500;
				padding: 0 15px;
				white-space: normal;
				line-height: 22px;
				word-break: break-all;
				height: 44px;
				text-align: justify;
				text-indent: 0;
				z-index: 1;
			}
			.page_infoDetail .kr-article .kr-table-view .table-view-cell {
				padding: 11px;
			}
			.page_infoDetail .kr-article .kr-table-view .table-view-cell>a:not(.btn) {
				margin: -11px;
			}
			.page_infoDetail .kr-article .kr-table-view .media-object {
				max-width: 90px;
				width: 90px;
				height: 90px;
				line-height: 90px;
				overflow: hidden;
			}
			.page_infoDetail .kr-article .kr-table-view .media-object img {
				height: 90px;
			}
			.page_infoDetail .kr-article .kr-table-view .media-body {
				position: relative;
				font-size: 14px;
				white-space: normal;
				height: 90px;
			}

			.page_infoDetail .kr-article-banner {
				height: 180px;
				position: relative;
				overflow: hidden;
			}
			.page_infoDetail .kr-article-cover {
				background-position: center;
				background-color: #ccc;
				background-size: cover;
				background-repeat: no-repeat;
				width: 100%;
				height: 180px;
				position: relative;
			}
			.page_infoDetail .kr-article-cover img {
				width: 100%;
			}
			.page_infoDetail .kr-article-bar a {
				color: gray;
			}
			.page_infoDetail .kr-article-title {
				position: absolute;
				left: 15px;
				bottom: 15px;
				width: 90%;
				font-size: 16px;
				font-weight: 400;
				line-height: 21px;
				color: white;
				z-index: 11;
			}
			.page_infoDetail .kr-article-content {
				background: #efeff4;
			}
			.page_infoDetail .kr-article-meta {
				padding: 10px 0 10px 20px;
				display: table;
			}
			.page_infoDetail .kr-article-meta div {
				height: 30px;
				font-size: 15px;
				display: table-cell;
				vertical-align: middle;
				color: gray;
				padding: 0 5px;
			}
			.page_infoDetail .kr-article-avatar img {
				width: 30px;
			}
			.page_infoDetail .kr-article-text {
				font-size: 13px!important;
			}
			.page_infoDetail .kr-article-author,
			.page_infoDetail .kr-article-time {
				font-weight: 600;
			}
			.page_infoDetail .kr-article-article {
				font-size: 15px;
				padding: 0 15px;
				color: #000;
			}
			.page_infoDetail .kr-article-article ol {
				margin: 0 0 0 20px;
				padding: 0;
			}
			.page_infoDetail .kr-article-article p {
				color: #000;
				font-size: 15px;
				line-height: 23px;
			}

			.page_infoDetail .kr-article-article img,
			.page_infoDetail .kr-article-article iframe {
				max-width: 100%;
				width: 100%;
				height: auto;
			}
			.page_infoDetail embed,object{
				display: none;
			}
			.page_infoDetail .kr-article-content {}
			.page_infoDetail .kr-browser .bar .btn {
				top: 10px;
				padding: 2px 12px 2px;
			}
			.page_infoDetail .kr-browser-bar .spinner {
				width: 20px;
				height: 20px;
			}
			.page_infoDetail .kr-browser-bar a {
				color: gray;
			}
		</style>
		<div class="page_infoDetail">
			<!--顶部banner图 开始-->
			<div id="kr-article-banner" class="kr-article-banner">
				<div id="kr-article-cover" class="kr-article-cover">
					<img :src="form.cover">
				</div>
				<h2 id="kr-article-title" class="kr-article-title">{{form.title}}</h2>
			</div>
			<!--顶部banner图 结束-->
			
			<div class="kr-article-content">
				<!-- 文章作者、发布时间等信息 -->
				<div class="kr-article-meta">
					<div id="kr-article-author" class="kr-article-author">{{form.author}}</div>
					<div class="kr-article-text">发表于</div>
					<div id="kr-article-time" class="kr-article-time">{{form.time}}</div>
				</div>
				<!--文章详细内容-->
				<div id="kr-article-article" class="kr-article-article" v-html="form.content"></div>
			</div>
		</div>

		<script type="text/javascript">
			(function(){
				new Vue({
					el: '.page_infoDetail',
					data: {
						form:{
							cover: '',
							title: '',
							author: '',
							time: '',
							content: ''
						}
					},
					mounted() {
						$.showLoading();
						this.getPostData();
					},
					methods: {
						getPostData() {
							let guid = page_id.guid;
							if(!guid) {
								return;
							}
							//前页传入的数据，直接渲染，无需等待ajax请求详情后
							this.form.cover = page_id.cover;
							this.form.title = page_id.title;
							this.form.author = page_id.author;
							this.form.time = page_id.time;
							//向服务端请求文章详情内容
							let self = this;
							$.ajax('http://spider.dcloud.net.cn/api/news/36kr/' + guid, {
								type:'GET',
								dataType: 'json', //服务器返回json格式数据
								timeout: 15000, //15秒超时
								success: function(rsp) {
									self.form.content = rsp.content;
									$.hideLoading();
								},
								error: function(xhr, type, errorThrown) {
									$.hideLoading();
									$.toast('获取文章内容失败');
									//TODO 此处可以向服务端告警
								}
							});
						}
					}
				});
			})();
		</script>