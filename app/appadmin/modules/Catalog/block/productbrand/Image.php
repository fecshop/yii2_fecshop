<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Catalog\block\productbrand;

use Yii;
use yii\base\BaseObject;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Image extends BaseObject
{
    public function upload()
    {
        foreach ($_FILES as $FILE) {
            list($imgSavedRelativePath, $imgUrl, $imgPath) = Yii::$service->category->image->saveCategoryUploadImg($FILE);
        }
        echo json_encode([
            'return_status' => 'success',
            'relative_path' => $imgSavedRelativePath,
            'img_url'        => $imgUrl,
        ]);
        exit;
    }
}
