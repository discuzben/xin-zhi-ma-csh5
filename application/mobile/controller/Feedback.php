<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2019/1/9
 * Time: 9:57
 */

namespace app\mobile\controller;

use Exception;
use Validate;
use Db;

use app\common\lib\UploadImage;
use app\common\util\Image;
use app\mobile\model\Feedback as MFeedback;
use app\mobile\model\FeedbackImage;

class Feedback extends BaseController
{
    // 保存建议反馈
    public function save()
    {
        $data = request()->post();
        $validator = Validate::make([
            'feedback' => 'require' ,
            'contact' => 'require' ,
            'score' => 'require' ,
        ] , [
            'feedback.require'  => '问题和意见必须提供' ,
            'contact.require'   => '联系方式必须提供' ,
            'score.require'  => '应用评分必须提供' ,
        ]);
        if (!$validator->check($data)) {
            return c_response('0001' , $validator->getError());
        }
        $data['user_id'] = user()->uid;
        $image = isset($_FILES['image']) ? $_FILES['image'] : [];
        if (UploadImage::count($image) > 0) {
            $image = Image::multiple($image);
            if ($image['status'] != 'success') {
                return c_response('0001' , $image['msg']);
            }
            $image = $image['data']['success'];
        } else {
            $image = [];
        }
        try {
            Db::startTrans();
            $feedback = new MFeedback();
            $feedback->allowField([
                'user_id' ,
                'feedback' ,
                'contact' ,
                'score'
            ])->save($data);
            if (!empty($image)) {
                foreach ($image as $v)
                {
                    $v['feedback_id'] = $feedback->id;
                    $feedback_image = new FeedbackImage();
                    $feedback_image->allowField([
                        'feedback_id' ,
                        'name' ,
                        'size' ,
                        'url' ,
                        'path'
                    ])->save($v);
                }
            }
            Db::commit();
            return c_response('0000' , '' , $feedback->id);
        } catch(Exception $e) {
            Db::rollBack();
            throw $e;
        }
    }
}