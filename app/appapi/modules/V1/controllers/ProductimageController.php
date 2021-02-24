<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appapi\modules\V1\controllers;

use fecshop\app\appapi\modules\AppapiTokenController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class ProductimageController extends AppapiTokenController
{
    public $numPerPage = 5;
    
    /**
     * @post Param imgFileName | string 图片名称
     * @post Param imgFileBase64Code | string， base64格式的字符串
     */
    public function actionSync()
    {
        //echo 1;exit;
        $fileName = Yii::$app->request->post('imgFileName');
        $imgFileBase64Code = Yii::$app->request->post('imgFileBase64Code');
        $imgFileSaveRelativePath = Yii::$app->request->post('imgFileSaveRelativePath');
        $errors = '';
        if (!$fileName) {
            $errors .= 'post param imgFileName is empty, ';
        }
        if (!$imgFileBase64Code) {
            $errors .= 'post param imgFileBase64Code is empty, ';
        }
        if ($errors ) {
            return [
                'code'    => 400,
                'message' => 'post param error',
                'data'    => [
                    'errors' => $errors
                ],
            ];
        }
        //preg_match('/^(data:\s*image\/(\w+);base64,)/', $imgFileBase64Code, $result);
        //var_dump($result);exit;
        $fileStream=base64_decode($imgFileBase64Code);
        if (!$fileStream) {
            $errors .= 'post param imgFileBase64Code is not filebase64code, ';
        }
        if ($errors ) {
            return [
                'code'    => 400,
                'message' => 'post param error',
                'data'    => [
                    'errors' => $errors
                ],
            ];
        }
        //echo $fileStream;exit;
        list($imgSavedRelativePath, $imgUrl, $imgPath, $newName) = Yii::$service->product->image->saveProductStreamImg($fileName, $fileStream, $imgFileSaveRelativePath);
        if (!$imgUrl ||  !$imgPath) {
            return [
                'code'    => 400,
                'message' => 'save product stream img fail',
                'data'    => [
                    'errors' => Yii::$service->helper->errors->get(', '),
                ],
            ];
        }
        return [
            'code'    => 200,
            'message' => 'save product stream img success',
            'data'    => [
                'imgUrl' => $imgUrl,
                'imgRelativePath' => $imgSavedRelativePath,
                'imgFileName' => $newName,
            ],
        ];
            
    }
    
}