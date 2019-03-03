<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Cms\controllers;

use fecshop\app\appadmin\modules\Cms\CmsController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class XeditorController extends CmsController
{
    public $enableCsrfValidation = false;

    // cms/staticblock/imageupload
    public function actionImageupload()
    {
        //$imgUrl = 'http://fecshop.appadmin.fancyecommerce.com/assets/9e150533/dwz_jui-master/themes/default/images/logo.png';
        foreach ($_FILES as $FILE) {
            list($imgSavedRelativePath, $imgUrl, $imgPath) = Yii::$service->image->saveUploadImg($FILE);
        }
        exit(json_encode(['err' => 0, 'msg' => $imgUrl]));
    }

    public function actionFlashupload()
    {
    }

    public function actionLinkupload()
    {
    }

    public function actionMediaupload()
    {
    }
}
