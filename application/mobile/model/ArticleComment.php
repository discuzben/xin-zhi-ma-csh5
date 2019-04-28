<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2018/11/12
 * Time: 13:47
 */

namespace app\mobile\model;

use Exception;
use think\Model;

class ArticleComment extends Model
{
    // 获取给定文章下 + 某个用户的评论
    public static function list($article_id , $user_id){

    }

    // 单条：数据处理
    public static function single($obj){
        // 评论的人
        $obj->user = User::get($obj->user_id);
        // 回复的用户
        $obj->reply_user = self::replyUser($obj->pid);
        // 该评论的回复
        $obj->simple_reply = self::simpleReply($obj->id);
    }

    // 多条：数据处理
    public static function multiple($objs){
        foreach ($objs as $v)
        {
            self::single($v);
        }
    }

    // 获取单条评论下的简略回复
    public static function simpleReply($id){
        $id_list = self::idList($id);
        $id_list = array_slice($id_list , 0 , 3);
        $data = self::whereIn('id' , $id_list)->select();
        self::multiple($data);
        return $data;
    }

    // 获取给定评论下所有子集评论id
    public static function idList($pid , $id = null){
        if (empty($pid)) {
            throw new Exception("必须提供有效的上级评论id");
        }
        // 如果未提供 id，则正常选区
        // 如果提供了 id，则取出 < id 的内容
        $find = function($_id_ , &$res = []) use(&$find , $id){
            if (empty($id)) {
                $data = self::where('pid' , $_id_)->order('id' , 'desc')->select();
            } else {
                $data = self::where([
                    ['pid' , '=' , $_id_] ,
                    ['id' , '<=' , $id]
                ])->order('id' , 'desc')->select();
            }
            foreach ($data as $v)
            {
                $res[] = $v->id;
                $find($v->id , $res);
            }
            return $res;
        };
        $id_list = $find($pid);
        return $id_list;
    }

    // 评论回复的用户
    public static function replyUser($id){
        $data = self::get($id);
        if (is_null($data)) {
            return null;
        }
        return User::get($data->user_id);
    }
}