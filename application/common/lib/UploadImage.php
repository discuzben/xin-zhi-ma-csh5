<?php
namespace app\common\lib;

class UploadImage extends UploadFile {
    public static $mime = ['image/gif' , 'image/jpeg' , 'image/png'];
    /*
     * 获取文件数组中的图片文件
     */
    public static function images(array $files = []){
        if (empty($files)) {
            return null;
        }
        if (!is_array($files['tmp_name'])) {
            if (in_array($files['type'] , self::$mime)) {
                return $files;
            }
            return null;
        }
        $len = count($files['tmp_name']);
        $res = [
            'name'	   => [] ,
            'size'	   => [] ,
            'type'	   => [] ,
            'tmp_name' => [] ,
            'error'	   => []
        ];
        for ($i = 0; $i < $len; ++$i)
        {
            if (in_array($files['type'][$i] , self::$mime)) {
                $res['name'][$i]     = $files['name'][$i];
                $res['size'][$i]     = $files['size'][$i];
                $res['type'][$i]     = $files['type'][$i];
                $res['tmp_name'][$i] = $files['tmp_name'][$i];
                $res['error'][$i]    = $files['error'][$i];
            }
        }
        return $res;
    }
    /*
     * 上传文件类型为图片时：单个
     * @param  Array    $images		        待处理图片路径
     * @param  Boolean  $save_origin		是否保留原名
     * @param  Mixed
     */
    public function save(array $image = array() , $save_origin = false){
        $save_origin = is_bool($save_origin) ? $save_origin : false;
        // 获取上传文件中的图片
        $image = self::images($image);
        if ($image === false) {
            return false;
        }

        return parent::save($image , $save_origin);
    }
    /*
     * 上传文件类型为图片时：多个
     * @param  Array    $images					    待处理图片路径
     * @param  Boolean  $save_origin		是否保留原名
     * @param  Mixed
     */
    public function saveAll(array $images = array() , $save_origin = false){
        $save_origin = is_bool($save_origin) ? $save_origin : false;

        // 获取上传文件中的图片
        $images = self::images($images);
        return parent::saveAll($images , $save_origin);
    }

    // 检查是否全部都是图片
    public static function isImage(array $files = []){
        if (empty($files)) {
            return false;
        }
        if (!is_array($files['tmp_name'])) {
            return in_array($files['type'] , self::$mime);
        }
        $len = count($files['tmp_name']);
        for ($i = 0; $i < $len; ++$i)
        {
            if (!in_array($files['type'][$i] , self::$mime)) {
                return false;
            }
        }
        return true;
    }

    // 检查图片数量
    public static function count(array $files = []){
        if (empty($files)) {
            return 0;
        }
        if (!is_array($files['tmp_name'])) {
            if (in_array($files['type'] , self::$mime)) {
                return 1;
            }
            return 0;
        }
        $len    = count($files['tmp_name']);
        $count  = 0;
        for ($i = 0; $i < $len; ++$i)
        {
            if (in_array($files['type'][$i] , self::$mime)) {
                $count++;
            }
        }
        return $count;

    }
}