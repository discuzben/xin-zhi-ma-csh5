<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2018/11/12
 * Time: 9:26
 */

namespace app\mobile\model;

use think\Model;
use app\common\lib\Category;

class ArticleType extends Model
{
    /**
     * @title 生成层级文章分类
     *
     * @param int       $id             上级分类id
     * @param boolean   $save_structure 是否保存数据层级结构
     * @return array
     */
    public static function type($id = null , $save_structure = true){
        $data = self::select()->toArray();
        $group = Category::childrens(null , $data , null , true , $save_structure);
        return $group;
    }

    /**
     * @title 获取所有相关分类id
     * @param name:id type:string require:1 desc:分类描述
     * @return array
     */
    public static function idList($id = null){
        $data = self::type($id , false);
        $id_list = [];
        foreach ($data as $v)
        {
            $id_list[] = $v['id'];
        }
        return $id_list;
    }
}