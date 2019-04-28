<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2018/11/9
 * Time: 14:55
 *
 * 杂项
 */

namespace app\api\controller;

use Exception;
use app\mobile\utils\Repayment;
use app\mobile\model\User;

// 生成的二维码相关
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;

/**
 * @title 杂项
 */
class Misc extends BaseController
{
    /**
     * @title 获取消费类型
     * @author by cxl
     *
     * @url /api/Misc/consumptionType
     * @method post
     *
     * @return null:object
     *
     */
    public function consumptionType(){
        try {
            // $consumption_type = Repayment::consumptionType();
            // return c_response(1 , '' , $consumption_type);
            $consumption_type = Repayment::consumptionType();
            return c_response(1 , '' , $consumption_type);
        } catch (Exception $e) {
            throw $e;
//            return c_response(2 , code_msg(2));
        }
    }

    /**
     * @title 生成二维码
     * @author by cxl
     * @description 生成二维码
     *
     * @url /api/Misc/qrcode
     * @method post
     *
     * @return null:string
     */
    public function qrcode(){
        $register   = config('app.share_register_url');
        $link       = $register . '?pro_code=' . user()->pro_code;

        // Create a basic QR code
        $qr_code = new QrCode($link);
        $qr_code->setSize(300);

        // Set advanced options
        $qr_code->setWriterByName('png');
        $qr_code->setMargin(10);
        $qr_code->setEncoding('UTF-8');
        $qr_code->setErrorCorrectionLevel(new ErrorCorrectionLevel(ErrorCorrectionLevel::HIGH));
        $qr_code->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]);
        $qr_code->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]);
        // $qr_code->setLabel('Scan the code', 16, __DIR__.'/../assets/fonts/noto_sans.otf', LabelAlignment::CENTER);
        $qr_code->setLogoPath(__DIR__.'/../res/logo.png');
        $qr_code->setLogoSize(100, 100);
        $qr_code->setRoundBlockSize(true);
        $qr_code->setValidateResult(false);
        $qr_code->setWriterOptions(['exclude_xml_declaration' => true]);

         // Directly output the QR code
         // header('Content-Type: '.$qr_code->getContentType());
//          header('Content-Type: image/png');
//          echo $qr_code->writeString();
        $mime = $qr_code->getContentType();
        $base64 = base64_encode($qr_code->writeString());
        $base64 = "data:{$mime};base64,{$base64}";
        // var_dump($link);
//         var_dump($base);
//          echo "<img src='{$base64}'>";
//         echo "<img src='data:image/png;base64,/{$base}'>";
        return c_response(1 , '' , $base64);
    }
}