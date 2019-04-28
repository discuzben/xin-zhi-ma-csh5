<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2018/10/30
 * Time: 11:59
 */

namespace app\mobile\model;

use think\Model;
use Db;
use think\Model\Collection;

class User extends Model
{
    protected $pk = 'uid';
    protected $field = [

    ];

    public function role(){
        return $this->belongsTo(Role::class , 'role_id' , 'id');
    }

    // 收益
    public function userIncome(){
        return $this->hasMany(UserIncome::class , 'uid' , 'uid');
    }

    // 单条：数据处理
    public static function single(User $obj = null){
        if (empty($obj)) {
            return ;
        }
        // 团队人数（直接）
        $obj->team_count = 1 + self::countForDirect($obj->uid);
        // 累计收益
        $obj->_total_income = $obj->total_income + $obj->total_income_fy;
        // 当日收益
        $obj->income_for_day = self::incomeForDay($obj->uid);
        // 当月收益
        $obj->income_for_month = self::incomeForMonth($obj->uid);
    }

    // 当日收益
    public static function incomeForDay($id)
    {
        $date = date('Y-m-d');
        return UserIncome::where('uid' , $id)
            ->whereRaw("date_format(create_time , '%Y-%m-%d') = '{$date}'")
            ->sum('income_amount');
    }

    // 当月收益
    public static function incomeForMonth($id)
    {
        $month = date('Y-m');
        return UserIncome::where('uid' , $id)
            ->whereRaw("date_format(create_time , '%Y-%m-%d') = '{$month}'")
            ->sum('income_amount');
    }

    // 直接下级
    public static function countForDirect($id){
        return self::where('p_uid' , $id)->count();
    }

    // 间接下级（所有下级人数）
    public static function countForAll($ps_uid){
        // 下级      uid     ps_uid
        // self     3       1,2,3
        // one      4       1,2,3,4
        // two      5       1,2,3,4,5
        // thr      6       1,2,3,4,5,6
        // ...
        return self::where('ps_uid' , 'like' , sprintf('%s,%%' , $ps_uid))->count();
    }

    // 多条：数据处理
    public static function multiple(Collection $objs){
        foreach ($objs as $v)
        {
            self::single($v);
        }
    }

    // 总：直接推git 广人数
    public static function directUserCount($id){
        return self::where('p_uid' , $id)->count();
    }

    // 总：间接推广人数
    public static function indirectUserCount($id){
        $count = 0;
        $ids = self::where('p_uid' , $id)->column('uid');
        $cal = function($id) use(&$cal , &$count){
            $users  = self::where('p_uid' , $id)->column('uid');
            $count += count($users);
            foreach ($users as $v)
            {
                $cal($v);
            }
        };
        foreach ($ids as $v)
        {
            $cal($v);
        }
        return $count;
    }

    // 分类：直接推广
    public static function directTypeData($id){
        $data = [
            // 服务商
            'service' => 0 ,
            // 经纪人
            'broker' => 0 ,
            // 普通用户
            'general' => 0
        ];
        $data['service'] = self::where([
            ['p_uid' , '=' , $id] ,
            ['role_id' , '=' , 5] ,
        ])->count();
        $data['broker'] = self::where([
            ['p_uid' , '=' , $id] ,
            ['role_id' , '=' , 6] ,
        ])->count();
        $data['general'] = self::where([
            ['p_uid' , '=' , $id] ,
            ['role_id' , '=' , 7] ,
        ])->count();
        return $data;
    }

    // 分类：间接推广
    public static function indirectTypeData($id){
        $data = [
            // 服务商
            'service' => 0 ,
            // 经纪人
            'broker' => 0 ,
            // 普通用户
            'general' => 0
        ];
        $count = function($id , $type) use(&$count , &$data){
            switch ($type)
            {
                case 'service':
                    $ids = self::where([
                        ['p_uid' , '=' , $id] ,
                        ['role_id' , '=' , 5] ,
                    ])->column('uid');
                    $data['service'] += count($ids);
                    break;
                case 'broker':
                    $ids = self::where([
                        ['p_uid' , '=' , $id] ,
                        ['role_id' , '=' , 6] ,
                    ])->column('uid');
                    $data['broker'] += count($ids);
                    break;
                case 'general':
                    $ids = self::where([
                        ['p_uid' , '=' , $id] ,
                        ['role_id' , '=' , 7] ,
                    ])->column('uid');
                    $data['general'] += count($ids);
                    break;
            }
            foreach ($ids as $v)
            {
                $count($v , $type);
            }
        };
        $ids = self::where('p_uid' , $id)->column('uid');
        foreach ($ids as $v)
        {
            $count($v , 'service');
            $count($v , 'broker');
            $count($v , 'general');
        }
        return $data;
    }

    // 单条：安全的手机号码
    public static function safePhone($phone = ''){
        if (empty($phone)) {
            return $phone;
        }
        $head = substr($phone , 0 , 3);
        $tail = substr($phone , 7 , 4);
        // 安全的手机号码
        return $head . str_repeat('*' , 4) . $tail;
    }

    // 获取给定用户手机号码
    public static function getPhone($uid){
        return self::where('uid' , $uid)->value('phone');
    }

    // 获取用户
    public static function user($where = [])
    {
        $res = self::with('role')->where($where)->find();
        self::single($res);
        if (isset($res->role)) {
            Role::single($res->role);
        }
        return $res;

    }
}