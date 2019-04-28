<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2018/12/19
 * Time: 15:51
 *
 * 还款
 */

namespace app\mobile\controller;

use Exception;

use Validate;
use Db;

use app\mobile\utils\Page;
use app\mobile\utils\Repayment as URepayment;
use app\mobile\model\BankInfo;
use app\mobile\model\PayChannel;
use app\mobile\model\RepaymentPlan;
use app\mobile\model\RepaymentOrder;
use app\mobile\model\RepaymentDetail;

class Repayment extends BaseController
{
    // 还款管理
    public function cardManagerView()
    {
        return view('cardManager');
    }

    // 智能还款
     public function repaymentManagerView()
     {
         return view('repaymentManager');
     }

     // 修改卡信息
     public function repaymentChangeCardInfoView()
     {
         return view('repaymentChangeCardInfo');
     }

    // 还款列表
    public function repaymentPlanListView()
    {
        return view('repaymentPlanList');
    }

    // 计划主列表
    public function repaymentMainPlanView()
    {
        return view('repaymentMainPlan');
    }
    // 计划详情
    public function repaymentplanDetailView()
    {
        return view('repaymentplanDetail');
    }

    /**
     * @title 生成还款计划
     * @author by cxl
     * @url /api/Repayment/customize
     * @method post
     *
     * @param name:date  type:string require:1 desc:实际字段名称为：date，还款日期，一个包含具体还款日期列表的数组，格式：["YYYY-MM-DD","YYYY-MM-DD"]
     * @param name:total_amount type:string require:1 desc:还款总额
     * @param name:pay_channel_id type:string require:1 desc:支付通道
     * @param name:count type:string require:1 desc:每日还款笔数
     *
     * @return null:object
     */
    public function customize(){
        $data = request()->post();
        $validator = Validate::make([
            'pay_channel_id' => 'require' ,
            'total_amount'  => 'require' ,
            'date'          => 'require' ,
            'count'         => 'require'
        ] , [
            'pay_channel_id.require'        => '请选择支付通道' ,
            'total_amount.require'          => '请提供还款总金额' ,
            'date.require'                  => '请提供还款日期' ,
            'count.require'                 => '请提供每日还款笔数' ,
        ]);
        if (!$validator->check($data)) {
            return c_response('0001' , $validator->getError());
        }
        $data['date'] = json_decode($data['date'] , true);
        // 检查日期是否大于当前日期
        $cur_date = date('Y-m-d');
        foreach ($data['date'] as $v)
        {
            if ($cur_date >= $v) {
                return c_response('0001' , '还款日期必须全部大于当前日期');
            }
        }
        // 检查是否满足最低限额
        $pay_channel = PayChannel::get($data['pay_channel_id']);
        // 总金额
        $data['total_amount'] = intval($data['total_amount']);
        // 还款天数
        $data['day_count'] = count($data['date']);
        // 每日最高还款笔数
        $max_repay_count_for_day    = config('app.repay_count_for_day');
        $repay_count_for_day        = isset($data['count']) && !empty($data['count']) ? intval($data['count']) : $max_repay_count_for_day;
        if ($repay_count_for_day > $max_repay_count_for_day || $repay_count_for_day < 1) {
            return c_response('0001' , "每日还款笔数超出范围：请提供正确的数值，取值范围：1 - {$max_repay_count_for_day}");
        }
        // 还款笔数
        $count = $data['day_count'] * $repay_count_for_day;
        // 单笔还款金额
        $data['per_repayment_amount'] = floor($data['total_amount'] / $count);
        if ($data['per_repayment_amount'] < $pay_channel->limit_amount_min && $data['per_repayment_amount'] > $pay_channel->limit_amount_max) {
            // todo 需要修改 cs_pay_channel 中的 limit_amount_min & limit_amount_max
            // todo 需要需改成和智能还款接口一致的数值，具体数值可以参考 config/app.php 中 min_per_repayment_money
            return c_response('0001' , "超出通道限制的单笔金额范围！请控制单笔金额在：{$pay_channel->limit_amount_min}-{$pay_channel->limit_amount_max}");
        }
        // 通道费率
        $channel_rate = $pay_channel->channel_user_rate / 100 / 100;
        // 账单类型-- 1 完美账单（可选消费区域/消费类型） 2-完美账单（自动生成消费区域/消费类型）
        $advanced = $data['pay_channel_id'] == 1 ? true : false;
        // 自动生成还款计划
        $detail = URepayment::planDetail($data['date'] , $data['total_amount'] , $data['per_repayment_amount'] , $repay_count_for_day , $channel_rate , $advanced , $data['consumption_type'] ?? '');
        if ($detail == false) {
            return c_response('0001' , '生成还款计划失败');
        }
        $res = [
            // 总金额
            'total' => $data['total_amount'] ,
            // 还款日期
            'date'  => $data['date'] ,
            // 计划明细
            'detail' => $detail
        ];
        return c_response('0000' , '' , $res);
    }

    /**
     * @title 新增还款计划
     * @author by cxl
     * @description 新增还款计划
     *
     * @url /api/Repayment/save
     * @method post
     *
     * @param name:bankcard_id type:string require:1 desc:银行卡id
     * @param name:pay_channel_id type:string require:1 desc:通道id
     * @param name:total_amount type:string require:1 desc:总金额
     * @param name:detail type:string require:1 desc:json字符串：调用生成还款计划api生成的数据，仅返回其中的 list 字段的数据！！
     * @param name:province type:string require:0 desc:省份（如果是完美账单-可选区域则必填）
     * @param name:city type:string require:0 desc:城市（如果是完美账单-可选区域则必填）
     *
     * @param name:channel_fee type:string require:0 desc:通道手续费（合计）
     * @param name:fee type:string require:0 desc:手续费（单笔手续费合计）
     * @param name:count type:string require:0 desc:还款笔数（合计）
     *
     * @return null:id
     *
     */
    public function save(){
        $data = request()->post();
        $validator = Validate::make([
            'bankcard_id'       => 'require' ,
            'pay_channel_id'    => 'require' ,
            'total_amount'      => 'require' ,
            'count'             => 'require' ,
            // 'repay_count'       => 'require' ,
            // 'trans_count'       => 'require' ,
            'detail'            => 'require' ,
            'channel_fee'       => 'require' ,
            'fee'               => 'require' ,
        ] , [
            'bankcard_id.require'           => '请先选择银行卡' ,
            'pay_channel_id.require'        => '请选择支付通道' ,
            'total_amount.require'          => '请提供还款总金额' ,
            'date.require'                  => '请提供还款日期' ,
            'count.require'                 => '请提供总还款笔数' ,
            // 'repay_count.require'           => '请提供总还款笔数' ,
            // 'trans_count.require'           => '请提供总交易次数' ,
            'detail.require'                => '请提供计划详情' ,
            'channel_fee.require'                => '请提供通道手续费（合计）' ,
            'fee.require'                => '请提供单笔手续费（合计）'
        ]);
        if (!$validator->check($data)) {
            return c_response(2 , $validator->getError());
        }
        // 如果是完美账单-可选区域，要求必须提供城市
        $data['province']   = $data['province'] ?? '';
        $data['city']       = $data['city'] ?? '';
        if ($data['pay_channel_id'] == 1 && empty($data['province'])) {
            return c_response('0001' , '完美账单-可选区域，必须提供省份');
        }
        if ($data['pay_channel_id'] == 1 && empty($data['city'])) {
            return c_response('0001' , '完美账单-可选区域，必须提供城市');
        }
        //$data['repay_count'] = intval($data['repay_count']);
        //$data['trans_count'] = intval($data['trans_count']);
        $data['count'] = intval($data['count']);
        // 通道费率
        $channel = PayChannel::get($data['pay_channel_id']);
        $data['fee_rate'] = $channel->channel_user_rate;
        $fee_rate = $data['fee_rate'] / 100 / 100;
        // 费用
        // $data['fee'] = $data['total_amount'] * $fee_rate;
        // 单笔手续费
        // $data['tip'] = config('app.service_fee') * $data['trans_count'];
        // todo 以下这两个由于前端提供的字段和数据库字段冲突，所以需要严格按照以下顺序进行填充
        // todo 否则就会出现 手续费 = 费率费用
        $data['tip'] = $data['fee'];
        $data['fee'] = $data['channel_fee'];
        // 卡号
        $data['bankcard_no'] = BankInfo::getCardNo($data['bankcard_id']);
        // 执行状态 1-执行中 -执行成功 9-执行失败
        $data['status'] = 1;
        $data['uid'] = user()->uid;
        $data['create_time'] = time();
        // 通道类型：默认为 1，表示新通道
        $data['chantype'] = 1;
        // 还款模式 1-一扣一还 2-多扣一还
        $data['repay_mode'] = 2;
        // 还款笔数
        // $data['count']  = $data['repay_count'];
        $data['detail'] = json_decode($data['detail'] , true);
        // 订单号：dsorderid
        $data['dsorderid'] = URepayment::orderNumForPlan();

        try {
            // 保存
            Db::startTrans();
            /**
             * *********************************
             * 新增还款计划
             * *********************************
             */
            $plan = new RepaymentPlan();
            $plan->allowField([
                'total_amount' ,
                'count' ,
                'fee' ,
                'fee_rate' ,
                'tip' ,
                'pay_channel_id' ,
                // 'status' ,
                'uid' ,
                'bankcard_id' ,
                'bankcard_no' ,
                'create_time' ,
                'province' ,
                'city' ,
                'dsorderid' ,
                'repay_mode'
            ])->save($data);
            foreach ($data['detail'] as $v)
            {
                if (isset($v['id'])) {
                    unset($v['id']);
                }
                /**
                 * ***********************
                 * 新增还款计划中的单笔订单
                 * ***********************
                 */
                $v['repayment_plan_id'] = $plan->id;
                $v['p_amount'] = $v['amount'];
                $v['p_time'] = $v['timestamp'];
                // 执行状态 0-已保存 1-执行中 2-执行成功 3- 9-执行失败，具体请看数据库
                $v['status'] = 0;
                $repayment_order = new RepaymentOrder();
                $repayment_order->allowField([
                    'repayment_plan_id' ,
                    'ordersn' ,
                    'p_amount' ,
                    'p_time' ,
                    'status' ,
                    // 'rate_money' ,
                    'fix_amount'
                ])->save($v);
                /**
                 * **************************************
                 * 新增还款计划单笔订单中的单次交易
                 * **************************************
                 */
                $detail = array_merge($v['detail']['deduction'] , [$v['detail']['repayment']]);
                foreach ($detail as $v1)
                {
                    if (isset($v1['id'])) {
                        unset($v1['id']);
                    }

                    $v1['order_id'] = $repayment_order->id;
                    $repayment_detail = new RepaymentDetail();
                    $repayment_detail->allowField([
                        'order_id' ,
                        'repay_orderid' ,
                        'trade_time' ,
                        // 'act_amount' ,
                        'trade_money' ,
                        'transfer_money' ,
                        'transfer_time' ,
                        'transfer_repay_orderid' ,
                        'repay_order_flag' ,
                        'rate_money' ,
                        'mcc'
                    ])->save($v1);
                }
            }
            // 新增还款明细
            Db::commit();
            return c_response('0000' , '' , $plan->id);
        } catch (Exception $e) {
            Db::rollback();
            throw $e;
        }
    }

    /**
     * @title 统计金额
     * @author by cxl
     * @description 个人统计金额
     *
     * @url /api/Repayment/statistics
     * @method post
     *
     * @return null:object
     */
    public function statistics(){
        $res = RepaymentPlan::where([
            ['uid' , '=' , user()->uid] ,
            ['status' , '=' , 2]
        ])->field(['sum(total_amount) as repayment_amount' , 'sum(total_amount + fee + tip) as consumption_amount'])
            ->find();
        return c_response('0000' , '' , $res);
    }

    /**
     * @title 还款计划列表
     * @author by cxl
     * @description 还款计划列表
     *
     * @url /api/Repayment/plans
     * @method post
     *
     * @param name:bankcard_id type:string require:1 desc:银行卡id
     * @param name:pay_channel_id type:string require:1 desc:支付通道id
     * @param name:page type:string require:0 desc:页数
     * @param name:status type:string require:0 desc:状态
     *
     * @return null:array
     */
    public function plans(){
        $data = request()->post();



        $validator = Validate::make([
            // 这里可以增加检索条件
            'bankcard_id' => 'require' ,
            'pay_channel_id' => 'require' ,
        ] , [
            'bankcard_id.require' => '请选择银行卡' ,
            'pay_channel_id.require' => '请选择支付通道' ,
        ]);
        if (!$validator->check($data)) {
            return c_response('0001' , $validator->getError());
        }
        $data['status'] = $data['status'] ?? 'all';
        $where = [
            ['uid' , '=' , user()->uid] ,
            ['bankcard_id' , '=' , $data['bankcard_id']] ,
            ['pay_channel_id' , '=' , $data['pay_channel_id']]
        ];
        if ($data['status'] != 'all') {
            $where[] = ['status' , '=' , $data['status']];
        } else {
            $where[] = ['status' , '<>' , 0];
        }
        $count = RepaymentPlan::where($where)->count();

       // var_dump($count);die;

        $page = Page::deal($count);

        $res = RepaymentPlan::where($where)
            ->order('create_time' , 'desc')
            ->limit($page['offset'] , $page['limit'])
            ->select();
        RepaymentPlan::multiple($res);
        $res= Page::data($page , $res);
        return c_response('0000' , ''  , $res);
    }

    /**
     * @title 还款订单列表
     * @author by cxl
     * @description 还款订单列表
     *
     * @url /api/Repayment/orders
     * @method post
     *
     * @param name:id type:string require:1 desc:计划id，cs_repayment_plan.id
     * @param name:page type:string require:0 desc:页数
     * @return null:array
     */
    public function orders(){
        $data = request()->post();
        $validator = Validate::make([
            'id' => 'require'
        ] , [
            'id.require' => '请选择计划'
        ]);
        if (!$validator->check($data)) {
            return c_response('0001' , $validator->getError());
        }
        $where = [
            ['repayment_plan_id' , '=' , $data['id']]
        ];
        $count = RepaymentOrder::where($where)->count();
        $page = Page::deal($count);
        $res = RepaymentOrder::where($where)
            ->order('p_time' , 'asc')
            ->limit($page['offset'] , $page['limit'])
            ->select();
        RepaymentOrder::multiple($res);
        $res= Page::data($page , $res);
        return c_response('0000' , ''  , $res);
    }

    /**
     * @title 交易记录列表
     * @author by cxl
     * @description 交易记录列表
     *
     * @url /api/Repayment/details
     * @method post
     *
     * @param name:id type:string require:1 desc:订单id，cs_repayment_order.id
     * @return null:array
     */
    public function details(){
        $data = request()->post();
        $validator = Validate::make([
            'id' => 'require'
        ] , [
            'id.require' => '请选择订单'
        ]);
        if (!$validator->check($data)) {
            return c_response('0001' , $validator->getError());
        }
        $where = [
            ['order_id' , '=' , $data['id']]
        ];
        $res = RepaymentDetail::where($where)
            ->order('trade_time' , 'asc')
            ->select();
        RepaymentDetail::multiple($res);
        return c_response('0000' , ''  , $res);
    }

    public function  repaymentSet()
    {

        $cardid=input("post.cardid");
        return view("repayment_setPlan",["token"=>user()->token,'cardid'=>$cardid]);
    }

    public  function openUrl()
    {
        $bcuid=input("post.bcuid");

        return view("openurl",["bcuid"=>$bcuid]);

    }
}