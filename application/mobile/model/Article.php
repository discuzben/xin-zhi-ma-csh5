<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2018/11/12
 * Time: 9:36
 */

namespace app\mobile\model;

use think\Model;

class Article extends Model
{
    // 单条：数据处理
    public static function single($obj){
        // 获取文章分类
        $article_type = ArticleType::where('id' , $obj->article_type_id)->find();
        $obj->type = $article_type->name;
    }

    // 多条：数据处理
    public static function multiple($objs){
        foreach ($objs as $v)
        {
            self::single($v);
        }
    }
}