<?php /*a:1:{s:70:"/cs/docker/nginx/html/csh5/application/mobile/view/login/register.html";i:1545735395;}*/ ?>
		
		<style type="text/css">
		.page_register .div_logo{
			width: 100%;
			height: 200px;
			position: relative;
			background-color: white;
		}
		.page_register .div_logo img{
			width: 125px;
			height:200px;
			position: absolute;
			left: 50%;
			margin-left: -62.5px;
		}
		</style>

		<div class="page_register">
			<div id="div_logo" class="div_logo">
				<img src="/static/imgs/logo-2.png" />
			</div>
			<div class="weui-cells weui-cells_form" style="margin-top: 0">
			  <div class="weui-cell">
			    <div class="weui-cell__hd"><label class="weui-label">手机号：</label></div>
			    <div class="weui-cell__bd">
			      <input class="weui-input" type="number" placeholder="请输入手机号" v-model="form.phone">
			    </div>
			  </div>
			  <div class="weui-cell weui-cell_vcode">
			    <div class="weui-cell__hd">
			      <label class="weui-label">验证码：</label>
			    </div>
			    <div class="weui-cell__bd">
			      <input class="weui-input" type="tel" placeholder="请输入码证码" v-model="form.code">
			    </div>
			    <div class="weui-cell__ft">
			      <button class="weui-vcode-btn" @click='getCode'>获取验证码</button>
			    </div>
			  </div>
			  <div class="weui-cell">
			    <div class="weui-cell__hd"><label class="weui-label">密码：</label></div>
			    <div class="weui-cell__bd">
			      <input class="weui-input" type="number" pattern="[0-9]*" placeholder="请输入密码" v-model="form.password">
			    </div>
			  </div>
			  <div class="weui-cell">
			    <div class="weui-cell__hd"><label class="weui-label">确认密码：</label></div>
			    <div class="weui-cell__bd">
			      <input class="weui-input" type="number" pattern="[0-9]*" placeholder="请输入确认密码" v-model="form.confirm_password">
			    </div>
			  </div>
			  <div class="weui-cell">
			    <div class="mui-input-row" id="address">
					<div>
						<!--<p>自动获取地址（<span class="tip">不准确？</span><button type="button" @click='customize = true' class="customize-determine-pos">重新定位</button>）：</p>-->
						<p>自动获取地址（<span class="tip">不准确？开启GPS </span><button type="button" @click='getPosUseBaidu' class="weui-btn weui-btn_mini weui-btn_primary customize-determine-pos">重新定位</button>）：</p>
						<p>{{ addr }}</p>
					</div>
				</div>
			  </div>
			</div>
			<button type="button" class="weui-btn weui-btn_primary btn_login" style="margin-top: 10px;" @click="register">注册</button>

			<div style="margin-top: 10px;width: 100%;height: 40px;">
				<div class="weuiAgree" style="padding: 10px;">
					<input id="weuiAgree" class="weui-agree__checkbox" type="checkbox" name="" style="float: left; width: 15px;height: 15px;margin-top: 3.5px;">
					<span class="weui-agree__text"  style="display: inline;"><p>注册请阅读<a>《用户使用协议》</a></span>
				</div>
			</div>
		</div>

		<script src="http://api.map.baidu.com/api?v=2.0&ak=a9iOxeZvt8QyhCX5upQNdygbe5f7u9uj"></script>
		<script>
			new Vue({
                el:'.page_register',
                data: {
                    form: {
                        phone:null,
                        password:null,
                        confirm_password:null,
                        invite_user_phone:null,
                        code: null ,
                        pro_code: '' ,
						province: '' ,
						city: '' ,
						area: '' ,
						longitude: '' ,
						latitude: ''
                    } ,
					province: '' ,
					city: '' ,
					area: '' ,
                    verifyCode: '' ,
                    // 发送验证码连接
                    verifyCodeUrl: genUrl('/mobile/Login/registerVerifyCode') ,
                    // 注册连接
                    registerUrl: genUrl('/mobile/Login/register') ,
					// 地址
					addr: '定位中...' ,
					// 手动定位
					customize: false
                },
                created () {
                    let qs = queryString();
                    this.form.pro_code = qs.pro_code;

                } ,
				mounted () {
					// 地区选择
                    this.getPosUseBaidu();
				} ,
                methods: {
					// 获取地理位置
					getPosUseBaidu () {
					    this.addr = '定位中...';
					    let self = this;
                        let geolocation = new BMap.Geolocation();
                        geolocation.getCurrentPosition(function(res){
                            if(this.getStatus() == BMAP_STATUS_SUCCESS){
                                // console.log('您的位置：'+res.point.lng+','+res.point.lat);
                                self.getPosName(res.point.lng , res.point.lat , (pos) => {
                                    self.addr = pos.address;
                                    let point	= pos.point;
                                    let addr	= pos.addressComponents;

                                    // console.log(pos);

                                    self.form.province = addr.province;
                                    self.form.city = addr.city;
                                    self.form.area = addr.district;
                                    self.form.longitude = point.lng;
                                    self.form.latitude = point.lat;
								});
                            }
                            else {
                                console.log('failed'+this.getStatus());
                                $.alert('自动定位失败，请重新定位！');
                            }
                        });
                    } ,

					// 获取位置说明
					getPosName (longitude , latitude , callback) {
                        let geo = new BMap.Geocoder();
                        geo.getLocation(new BMap.Point(longitude, latitude), callback);
					} ,

                    // 获取验证码
                    getCode() {
                        if (this.form.phone == null) {
                            return $.alert("请填写手机号码！")
                        }
                        let self = this;
                        $.ajax({
                            url: this.verifyCodeUrl ,
							method: 'post' ,
                            data: {
                                phone: this.form.phone
                            } ,
                            success (data) {
                                $.alert(data.msg);
                            }
                        });
                    },
                    // 注册
                    register() {
                        //验证数据
                        if (!this.form.phone || this.form.phone == "") {
                            return $.alert("请填写手机号！");
                        }
                        if (!this.form.code || this.form.code == "") {
                            $.alert("请填写验证码！");
                            return false;
                        }
                        if (!this.form.password || this.form.password == "") {
                            return $.alert("请填写密码！");
                        }
                        if (!this.form.confirm_password || this.form.confirm_password == "") {
                            return $.alert("请填写确认新密码！");
                        }
                        // if (!this.form.invite_user_phone || this.form.invite_user_phone == "") {
                        //     return layer.alert("请填写邀请人手机号码！");
                        // }
                        if (this.form.password != this.form.confirm_password) {
                            return $.alert("两次输入密码不相同，请重新输入！");
                        }
                        if (this.form.code != this.form.code) {
                            return $.alert("验证码输入错误！");
                        }
                        // 数据完善
                        this.form.vcode =  this.form.code;
                        $.ajax({
                            url: this.registerUrl ,
                            method: 'post' ,
                            data: this.form,
                            success(data) {
                                if (data.code != '0000') {
                                    return layer.alert(data.msg);
                                }
                                $.alert('注册成功' , {
                                    yes () {
                                        window.location.href = 'http://h5t.xinzhima.cn';
                                    }
                                });
                            }
                        });
                    },
                } ,
				watch: {
                    province (newV , oldV) {
                        if (this.customize) {
							this.form.province = newV;
						}
					} ,
                    city (newV , oldV) {
                        if (this.customize) {
                            this.form.city = newV;
                        }
					} ,
					area (newV , oldV) {
                        if (this.customize) {
                            this.form.area = newV;
                        }
					}
				}
		});
		</script>
	</body>

</html>
