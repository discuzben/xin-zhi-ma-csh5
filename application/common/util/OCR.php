<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2018/12/7
 * Time: 13:57
 *
 * 身份认证
 */

namespace app\common\util;

use Exception;
use app\common\util\Http;

class OCR
{
    /**
     * @title 图片识别
     * @description 参考接口文档 https://www.juhe.cn/docs/api/id/287
     * @pram string $image base64 图片（	图像数据，base64编码(不包含data:image/jpeg;base64,)，要求base64编码后大小不超过4M，最短边至少15px，最长边最大4096px,支持jpg/png/bmp格式）
     * @param string $side  位置 front-正面 back-背面
     *
     */
    public static function determine($image = '' , $side = ''){
        $key = config('ocr.key');
        $api = config('ocr.api');
        $response = Http::post($api , [
            'key'   => $key ,
            'image' => $image ,
            'side'  => $side
        ]);
        $res = json_decode($response , true);
        $res = self::handle($res , $side);
        return $res;
    }

    // 数据处理
    public static function handle($res , $side){
        switch ($side)
        {
            case 'front':
                if (
                    empty($res['result']['realname']) ||
                    empty($res['result']['sex']) ||
                    empty($res['result']['nation']) ||
                    empty($res['result']['born']) ||
                    empty($res['result']['address']) ||
                    empty($res['result']['idcard'])
                ) {
                    // 表示认证失败
                    $res['status'] = false;
                } else {
                    // 表示认证成功
                    $res['status'] = true;
                }
                break;
            case 'back':
                if (
                    empty($res['result']['begin']) ||
                    empty($res['result']['department']) ||
                    empty($res['result']['end'])
                ) {
                    // 表示认证失败
                    $res['status'] = false;
                } else {
                    // 表示认证成功
                    $res['status'] = true;
                }
                break;
            default:
                throw new Exception('不支持的照片类型');
        }
        return $res;
    }
}