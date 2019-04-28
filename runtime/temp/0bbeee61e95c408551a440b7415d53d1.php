<?php /*a:1:{s:77:"/cs/docker/nginx/html/csh5/application/mobile/view/repayment/cardManager.html";i:1546948001;}*/ ?>

<style type="text/css">
  .page_cardManager .weui-navbar{
    top: 0px;
  }
  .page_cardManager .table-view{
    list-style: none;
  }
  .page_cardManager .card {
    font-size: 14px;

    position: relative;

    overflow: hidden;

    margin: 10px;
    padding: 10px;


    border-radius: 2px;
    background-color: white;
    background-clip: padding-box;
    box-shadow: 0 1px 2px rgba(0, 0, 0, .3);
  }
  .page_cardManager .card
  {
    border-radius: 5px;
  }
  
  .page_cardManager .card  .bank_logo
  {
    width: 40px !important;
    height: 40px;
    display: block;
    float: left;
  }
  .page_cardManager .card-header{
    display: block;
    float: left;
    width: 100%;

  }
  .page_cardManager .card-header span
  {
    font-size: 24px;
    height: 46px;
    line-height: 46px;
    display: block;
    float: left;
  }
  .page_cardManager .btn_unbind{
    float: right;
  }
  .page_cardManager .weui-btn:after{
    width: 100%;
    height: 100%;
  }
  .page_cardManager .card-footer{
    position: relative;
  }
  .page_cardManager .btn_update{
    float: left;
    margin-top: 15px;
  }
  .page_cardManager .btn_plan_log{
    position: absolute;
    left: 50%;
    margin-left: -43px;
  }
  .page_cardManager .btn_replay_plan{
    float: right;
  }
  .page_cardManager .weui-btn:after {
    border: none;
  }
  /*.page_cardManager .card-content .table-view{
    margin-left: 1rem;
  }*/
</style>

<div class="page_cardManager">
  <div class="weui-tab">
    <div class="weui-navbar">
      <a class="weui-navbar__item weui-bar__item--on" href="#tab1">
        信用卡
      </a>
      <a class="weui-navbar__item" href="#tab2">
        使用说明
      </a>
    </div>
    <div class="weui-tab__bd">
      <div id="tab1" class="weui-tab__bd-item weui-tab__bd-item--active">
        <ul class="table-view">
              <div class="card" v-for="card in cards ">
                <!--页眉，放置标题-->
                <div class="card-header">
                  <img class="bank_logo" v-bind:src="'/static/imgs/credit/'+card.bank.logo" />

                  <span>{{card.bank_name}}</span>
                  <a  class="weui-btn weui-btn_mini weui-btn_primary btn_unbind" v-bind:cardno="card.card_no" v-on:click="unbindCard(card.card_no)">解 绑</a>
                </div>
                <!--内容区-->
                <div class="card-content">
                  <li class="table-view">{{card.card_no}}</li>
                  <li class="table-view">
                    <span>账单日{{card.bill_day}}日</span>
                    <span>还款日{{card.repayment_day}}日</span>
                  </li>
                </div>
                <!--页脚，放置补充信息或支持的操作-->
                <div class="card-footer">
                  <button type="button" class="weui-btn weui-btn_mini weui-btn_primary btn_update" @click = "openChangeCardInfo(card)">修改信息</button>
                  <button type="button" class="weui-btn weui-btn_mini weui-btn_primary btn_plan_log" v-on:click="repayment_yylb(card.id)">预约列表</button>
                  <button type="button" class="weui-btn weui-btn_mini weui-btn_primary btn_replay_plan" v-on:click="repayment_yyhk(card.id)">预约还款</button>
                </div>
              </div>


            </ul>
      </div>
      <div id="tab2" class="weui-tab__bd-item">
        <h1>页面制作中...</h1>
      </div>
    </div>
  </div>
</div>
<script>
  var isbind_url = "http://pay2.xinzhima.cn/index/leshuapay/isBind";
  //打开绑卡页面;
  function openBindPage(cardid) {
    //
    var bcu_url = "http://pay2.xinzhima.cn/index/leshuapay/BindUnionCard";
    var b_data = {
      "cardid": cardid,
      "channel_id": 1
    };

    $.post(bcu_url, b_data, function(data) {
      //console.log(JSON.stringify(data));
      $.hideLoading();
      if (data.code == 1 && data.data.bcuid > 0) {
        //id记录到localstorage中传输过去;
        var bcuid = data.data.bcuid;
        var winname="win_bindcard_url";
        var wintitle="授权绑定";
        var winurl="/mobile/repayment/openurl";
        var windata={"bcuid":bcuid};
        CreateKeeWindow.openWindow(winname,wintitle,winurl,windata)

      } else {
        $.alert(data.msg, "温馨提示");

      }

    })
  }




  var vm_cardmanager = new Vue({
      el:'.page_cardManager',
      data: {
        cards:[]
      },
      mounted () {
        this.getCreditCard();
      } ,
      methods: {
        //载入信用卡数据列表；
        getCreditCard(){
          let creditCardsUrl = '/mobile/BankCard/creditCards';

          let self = this;
          
          $.post({
              url: creditCardsUrl,
              success(res) {

               // console.log("信用卡列表:")
               // console.log(res)
                let curdata = res.data;
                
              //  console.log(res.data)
                if (curdata.length == 0) {
                  $.alert("暂无信用卡数据");
                }
                self.cards = curdata;
              }
          });
        },
        //解绑卡
        unbindCard(card_no) {

          var self=this;
         // console.log(card_no)
          $.confirm('确认要解除绑定吗？', "温馨提示",  function(e) {

            $.showLoading();
            //
            var data = {
              'card_no': card_no,
              'channel_id': 1
            };
            //解绑卡业务
            var url = "http://pay2.xinzhima.cn/index/leshuapay/UnbindQuickCard";
            $.post(
                    url,
                    data,
                    function(data) {
                      //
                      $.hideLoading();
                      $.alert(data.msg, "温馨提示");
                      if (data.code == 1) {
                        //如查解绑成功,刷新当前页面;
                        self.getCreditCard();
                      }
                    }
            )

          },function () {

          })
        },
        //修改卡信息
        openChangeCardInfo(it) {
          page_id = it;
          var winname = "win_syxq";
          var wintitle = "修改卡信息";
          var winurl = "/mobile/Repayment/repaymentChangeCardInfoView";
          var windata = {};
          CreateKeeWindow.openWindow(winname, wintitle, winurl, windata)
        },

        //预约还款
        repayment_yyhk(cardid) {
          $.showLoading();

          var data = {
            'cardid': cardid,
            'channelid': 1
          };

          //检查是否是第一次使用
          $.post(
                  isbind_url,
                  data,
                  function(data) {

                    //console.log(JSON.stringify(data));
                    //
                    if (data.code == 0) {
                      //绑定授权

                      //

                      $.confirm(data.msg,"温馨提示",
                              function () {
                                //确认,打开绑卡页面；
                                openBindPage(cardid);
                              },
                              function () {
                                //取消

                              })

                      $.hideLoading();




                    } else if (data.code == 1) {
                      //打开设置还款计划页面
                      var winname="win_repayment_setPlan";
                      var wintitle="制定还款计划";
                      var winurl="/mobile/repayment/repaymentSet";
                      var windata={"cardid":cardid};
                      CreateKeeWindow.openWindow(winname,wintitle,winurl,windata)

                      $.hideLoading();

                    } else if (data.code == 2) {
                      $.alert(data.msg);
                      $.hideLoading();
                    }

                  })

        },
        //预约列表
        repayment_yylb(cardid) {

          // var data = {
          //   'cardid': cardid,
          //   'channelid': 1
          // }
          // $.post(
          //         isbind_url,
          //         data,
          //         function(data) {
                    //
                    // if (data.code == 0) {
                    //   //绑定授权
                    //   $.confirm(data.msg, "确认操作",
                    //           function () {
                    //             $.showLoading();
                    //             openBindPage(cardid);

                    //           }, function() {

                    //           });

                    // } else if(data.code == 1){
                      //打开还款计划页面
                      page_id = cardid;
                      var winname="win_repayment_PlanList";
                      var wintitle="制定还款计划";
                      var winurl="/mobile/repayment/repaymentPlanListView";
                      var windata={"cardid":cardid};
                      CreateKeeWindow.openWindow(winname,wintitle,winurl,windata)
          //           }else if(data.code == 2){
          //             $.alert(data.msg);
          //           }


          //         }
          // )
        }

      },
      watch: {
      }
    });

</script>