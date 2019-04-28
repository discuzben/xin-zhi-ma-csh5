<?php /*a:1:{s:67:"/cs/docker/nginx/html/csh5/application/mobile/view/index/share.html";i:1545702975;}*/ ?>
<style>
    .page_share .r1, .page_share .r0
    {
        background: #1b99dc;
    }
    .page_share .r0 *
    {
        color: #ffffff;
    }
    .page_share .r0 i, .page_share .r0 span
    {
        height: 24px;
        line-height: 24px;
    }

    .page_share .r0 div:first-child
    {
        text-align:left;
        padding-left: 2%;
        font-size: 12px;
    }
    .page_share .r0 div:first-child i
    {
        margin-right: 3px;
    }
    .page_share .r0 div:nth-child(2)
    {
        text-align: center;
        font-size: 16px;
    }
    .page_share .r0 div:last-child
    {
        text-align:right;
        padding-right: 2%;
        font-size: 12px;
    }
    .page_share .r0 div:last-child i
    {
        margin-right: 3px;
    }
    .page_share .r1 #shareBac{
        width: 100%;
        position: fixed;
    }
    .page_share .r1 #shareqrcode{
        bottom: 120px;
        left: 50%;
        margin-left: -2.5rem;
        position: fixed;
        background-color: #FD913A;
        color: white;
        border-radius: 14px;
        width: 5rem;
        height: 2rem;
        font-size: 12px;
        font-weight: bold;
        outline: none;
    }
    .page_share #qrcodeimg{
        position: fixed;
        bottom: 200px;
        width: 120px;
        height: 120px;
        left: 50%;
        margin-left: -60px;
    }
</style>
<div class="page_share">
    <div class="weui-flex r0">
        <div class="weui-flex__item"><span></span></div>
        <div class="weui-flex__item">添加好友</div>
        <div class="weui-flex__item"><i class="font_family icon-zhuanfa"></i></div>
    </div>
    <div class="r1">
        <img id="shareBac" src="/static/imgs/shareimg1.jpg" />
        <!-- <img id="qrcodeimg" :src="form.src"> -->
        <button id="shareqrcode" type="button" style="" @click="share()">点击分享</button>
    </div>
</div>
<script>
    (function() {
        //设置图片的位置
        $('#shareBac').css({
            height:$(document.body).height()-25
        });
        new Vue({
            el:'.page_share',
            data: {
                // form:{
                //     src:''
                // }
            },
            mounted () {
                let Mailmyturl = '/mobile/share';
                $.showLoading();
                let self = this;
                $.post({
                    url: Mailmyturl,
                    success(res) {
                        console.log(res);
                        // this.form.src = res.data;
                        self.createQRCode(res);

                        $.hideLoading();
                    },
                    error(res) {
                        self.createQRCode(res);
                    }
                });
                // console.log(this.form)
            },
            methods: {
                share(){
                    $.alert('待完成')
                },
                createQRCode(res) {
                    var qrcodeimg = document.createElement('img');
                    qrcodeimg.id = qrcodeimg;
                    res.data = 'static/sdf.jpg';
                    qrcodeimg.src = res.data;
                    qrcodeimgSrc = data.data;
                    qrcodeimg.style.position = 'fixed';
                    qrcodeimg.style.bottom = '100px';
                    qrcodeimg.style.width = '120px';
                    qrcodeimg.style.height = '120px';
                    qrcodeimg.style.left = '50%';
                    qrcodeimg.style.marginLeft = '-60px';

                    $(".page_share .r1").append(qrcodeimg);

                    console.log(qrcodeimg)
                }
            },
        });
    })();

</script>
