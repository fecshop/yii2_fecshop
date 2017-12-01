<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\System\block\error;

use fec\helpers\CRequest;
use fec\helpers\CUrl;
use fecshop\app\appadmin\interfaces\base\AppadminbaseBlockEditInterface;
use fecshop\app\appadmin\modules\AppadminbaseBlockEdit;
use Yii;

/**
 * block cms\article.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Manageredit //extends AppadminbaseBlockEdit implements AppadminbaseBlockEditInterface
{
    //public $_saveUrl;

    //public function init()
    //{
    //    //$this->_saveUrl = CUrl::getUrl('cms/article/managereditsave');
    //    parent::init();
    //}

    // 传递给前端的数据 显示编辑form
    public function getLastData()
    {
        $primaryKey = Yii::$service->helper->errorHandler->getPrimaryKey();
        $primaryVal = Yii::$app->request->get($primaryKey);
        $errorHander = Yii::$service->helper->errorHandler->getByPrimaryKey($primaryVal);
        return $errorHander->attributes;
        //return [
        //    'editBar'     => $this->getEditBar(),
        //    'textareas'   => $this->_textareas,
        //    'lang_attr'   => $this->_lang_attr,
        //    'saveUrl'     => $this->_saveUrl,
        //];
    }
    /**
    public function setService()
    {
        $this->_service = Yii::$service->helper->errorHandler;
    }

    public function getEditArr()
    {
        return [
            [
                'label'=>'标题',
                'name'=>'category',
                'display'=>[
                    'type' => 'inputString',
                ],
            ],

            [
                'label'=>'状态码',
                'name'=>'code',
                'display'=>[
                    'type' => 'inputString',
                ],
            ],
            
            [
                'label'=>'Message',
                'name'=>'message',
                'display'=>[
                    'type' => 'inputString',
                ],
            ],
            
            [
                'label'=>'File',
                'name'=>'file',
                'display'=>[
                    'type' => 'inputString',
                ],
            ],
            
            [
                'label'=>'line',
                'name'=>'line',
                'display'=>[
                    'type' => 'inputString',
                ],
            ],
            
            [
                'label'=>'Ip',
                'name'=>'ip',
                'display'=>[
                    'type' => 'inputString',
                ],
            ],
            
            [
                'label'=>'Name',
                'name'=>'name',
                'display'=>[
                    'type' => 'inputString',
                ],
            ],

            [
                'label'=>'Url',
                'name'=>'url',
                'display'=>[
                    'type' => 'inputString',
                ],
            ],

            
        ];
    }
    
    */

}
