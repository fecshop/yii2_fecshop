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
class LanguagesController extends AppapiTokenController
{
    
    /**
     * Get Lsit Api：得到article 列表的api
     */
    public function actionIndex()
    {
        $langs = Yii::$service->fecshoplang->getAllLanguages();
        
        return [
            'code'    => 200,
            'message' => 'fetch all languages success',
            'data'    => $langs,
        ];
    }
    
}
