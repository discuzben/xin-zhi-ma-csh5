<?php /*a:1:{s:74:"/cs/docker/nginx/html/csh5/application/mobile/view/mailbox/getMailbox.html";i:1545659900;}*/ ?>

		<style>
			.page_getMailbox .mui-progressbar-success span {
				background-color: #4cd964;
			}
			.page_getMailbox .mui-progressbar-warning span {
				background-color: #f0ad4e;
			}
			.page_getMailbox .mui-progressbar-danger span {
				background-color: #dd524d;
			}
			.page_getMailbox .mui-progressbar-royal span {
				background-color: #8a6de9;
			}
			.page_getMailbox{
				/*background-color: #FFFFFF;*/
				text-align: center;
			}
		</style>

		<div class="page_getMailbox">
			<div id="account-pro">
				<h5 id="title-status" class="title-status"></h5>
				<img src="/static/imgs/mail/progress.png" style="width: 150px;height: 150px; border-radius: 50%;">
				<div class="htmleaf-container">
					<div class="htmleaf-content">
						<div id="progress" class="progress">
							<b id="progress_bar" class="progress__bar">
								<span id="progress_text" class="progress__text">
									Progress: <em>0%</em>
								</span>
							</b>
						</div>
					</div>
				</div>
				<button class="weui-btn weui-btn_primary" type="button" id="parse">开始解析</button>
				<button class="weui-btn weui-btn_warn" type="button" id="login" style="display: none;">断线重连</button>
				<!-- <button type="button" id="back-home">返回首页</button> -->
			</div>

			<script>
				(function(){
				 	let token = '<?php echo $token; ?>'

				 	// console.log(token);

					let progressbar = $('#account-pro');

					// socket链接
					let login = $('#login');

					let parse = $('#parse');

					let backHome = $('#back-home');

					let status = $('.title-status')[0];

					//new一个socket
					let ws = new WebSocket(webSocketLink);

					//监听socket
					ws.onopen = function() {
						console.log('ws 连接已经打开');
						status.innerText = 'ws 连接已经打开';
						wslogin.call(ws, token);
					};

					ws.onmessage = function(e) {
						messageEvent(progressbar, e.data);
					};

					ws.onclose = function(e) {
						console.log('连接已经关闭' + e.reason);
						status.innerText = '连接已经关闭';
					};

					ws.onerror = function(e) {
						console.log('错误' + translateType(e));
						status.innerText = '错误' + e;
						wslogin.call(ws, token);
					};

					parse.on("click", function() {
						wsparse.call(ws, page_id);
					});
					
					login.on("click", function() {
						ws = new WebSocket(link);
					});

				function wslogin(token) {
					this.send(translateType({
						intent: 'login',
						data: {
							token
						}
					}));
				}

				function wsparse(mailbox_id) {
					console.log(mailbox_id);
					this.send(translateType({
						intent: 'parse',
						data: {
							user_mailbox_id: mailbox_id,
							bank_code: []
						}
					}));
				}

				function messageEvent(bar, rep) {
					let repo;
					// document.getElementsByClassName("title-status")[0].innerText = 'ws 接收到数据,准备解析' + rep;
					console.log(rep)
					repo = translateType(rep);
					switch (repo.intent) {
						case 'login':
							loginEvent(repo);
							break;
						case 'progress':
							progressEvent(bar, repo.data.index / repo.data.count);
							break;
						case 'completed':
							completedEvent(repo.intent);
							break;
						case 'error':
							errorEvent(repo.msg);
						default:
							interruptEvent(repo);
							// console.log(repo.intent);
					}
				}
				
				function loginEvent(res) {
					console.log(translateType(res))
					document.getElementsByClassName("title-status")[0].innerText = res.msg;
					document.getElementsByClassName("title-status")[0].innerText = "点击开始解析账单";
				}

				function progressEvent(bar, rate) {
					//设置进度条进度
					console.log(rate);
					updatepro(rate * 100);
					

					// document.getElementsByClassName("percent")[0].innerText = (rate * 100).toFixed(2) + "%";
					document.getElementsByClassName("title-status")[0].innerText = "邮箱账单解析中...";
				}

				function completedEvent(intent) {
					console.log("ssdfaewr")
					if (intent == 'completed') {
						setTimeout(function(){
							console.log('解析完成');
							// 解析完成后重新加载首页,更新账单
							document.getElementsByClassName("title-status")[0].innerText = "解析已完成";
							updatepro(100);
						},1000)
					}
				}
				
				function interruptEvent(res) {
					document.getElementsByClassName("title-status")[0].style.color = 'red';
					document.getElementsByClassName("title-status")[0].innerText = "解析意外中断，请稍后再试或联系客服人员！" + res.msg;
					document.getElementById("login").style.display = 'inline';
				}

				function errorEvent(msg) {
					document.getElementsByClassName("title-status")[0].innerText ="解析发生错误，请稍后再试或联系客服人员！" + msg;
				}

				var $progress = $('.progress'),
					$bar = $('.progress__bar'),
					$text = $('.progress__text'),
					percent = 0,
					update, resetColors, speed = 200,
					orange = 30,
					yellow = 55,
					green = 85,
					timer;
				resetColors = function() {
					$bar.removeClass('progress__bar--green').removeClass('progress__bar--yellow').removeClass('progress__bar--orange')
						.removeClass('progress__bar--blue');
					$progress.removeClass('progress--complete');
				};

				function updatepro(s) {
					// let s = Math.random() * 1.8;
					$progress.addClass('progress--active');
					timer = setTimeout(function() {
						percent = s;
						percent = parseFloat(percent.toFixed(2));
						$text.find('em').text(percent + '%');
						if (percent >= 100) {
							percent = 100;
							$progress.addClass('progress--complete');
							$bar.addClass('progress__bar--blue');
							$text.find('em').text('Complete');
							document.getElementsByClassName("title-status")[0].innerText = "邮箱账单解析完成,返回首页查看账单";
						} else {
							if (percent >= green) {
								$bar.addClass('progress__bar--green');
							} else if (percent >= yellow) {
								$bar.addClass('progress__bar--yellow');
							} else if (percent >= orange) {
								$bar.addClass('progress__bar--orange');
							}
							speed = Math.floor(Math.random() * 900);
							// update();
						}
						$bar.css({
							width: percent-1 + '%'
						});
					}, speed);
				};
				setTimeout(function () {
					updatepro(0);
				}, 1);
				})();
			</script>
