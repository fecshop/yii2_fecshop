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
class StaticblockController extends CmsController
{
    public $enableCsrfValidation = true;

    public function actionIndex()
    {
        $data = $this->getBlock()->getLastData();

        return $this->render($this->action->id, $data);
    }

    public function actionManageredit()
    {
        $data = $this->getBlock()->getLastData();

        return $this->render($this->action->id, $data);
    }

    public function actionManagereditsave()
    {
        $data = $this->getBlock('manageredit')->save();
    }

    public function actionManagerdelete()
    {
        $this->getBlock('manageredit')->delete();
    }

    // cms/staticblock/imageupload
    public function actionImageupload()
    {
        //$imgUrl = 'http://fecshop.appadmin.fancyecommerce.com/assets/9e150533/dwz_jui-master/themes/default/images/logo.png';
        foreach ($_FILES as $FILE) {
            list($imgSavedRelativePath, $imgUrl, $imgPath) = Yii::$service->image->saveUploadImg($FILE);
        }
        exit(json_encode(['err' => 0, 'msg' => $imgUrl]));
        //var_dump($_FILES);
    }

}
