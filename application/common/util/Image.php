<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2018/11/1
 * Time: 11:00
 */

namespace app\common\util;

use app\common\lib\UploadFile;
use app\common\lib\UploadImage;

class Image
{
    private static $_instance = null;

    /**
     * @title 保存单张上传图片
     * @param $image 上传的图片对象（$_FILES['image']）
     * @return ['status' => 'success' , 'msg' => '' , 'data' => ['name' => '' , 'size' => '' , 'mime' , 'url' => '' ]]
     */
    public static function single($image){
        static::instance();
        if (UploadFile::emptyFile($image)) {
            return static::response('error' , '请提供上传图片');
        }
        if (UploadFile::isMultiple($image)) {
            return static::response('error' , '请采用单张图片上传的方式');
        }
        $is_save_origin = config('app.is_save_origin');
        $image = static::$_instance->save($image , $is_save_origin);
        $image['url'] = gen_url($image['path']);
        unset($image['path']);
        return static::response('success' , '' , $image);
    }

    /**
     * @title 保存多张上传图片
     * @param $image 上传的图片对象（$_FILES['image']）
     * @return ['status' => 'success' , 'msg' => '' , 'data' => ['success'=>[image...] , 'error' => [image...] ] ]
     */
    public static function multiple($image){
        static::instance();
        if (UploadFile::emptyFile($image)) {
            return static::response('error' , '请提供上传图片');
        }
        if (!UploadFile::isMultiple($image)) {
            return static::response('error' , '请采用多张图片上传的方式');
        }
        $images = static::$_instance->saveAll($image , true);
        foreach ($images['success'] as &$v)
        {
            $v['url'] = gen_url($v['path']);
//            unset($v['path']);
        }
        return static::response('success' , '' , $images);
    }

    // 对象实例
    public static function instance(){
        if (is_null(static::$_instance)) {
            $dir = config('app.image_dir');
            static::$_instance = new UploadImage($dir);
        }
    }

    // 返回的信息
    public static function response($status , $msg = '' , $data = null){
        return compact('status' , 'msg' , 'data');
    }

}