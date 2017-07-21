<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appserver\modules;

use fec\controllers\FecController;
use fec\helpers\CConfig;
use Yii;
use yii\base\InvalidValueException;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class AppserverController extends FecController
{
    public $blockNamespace;

    /**
     * init theme component property : $fecshopThemeDir and $layoutFile
     * $fecshopThemeDir is appfront base theme directory.
     * layoutFile is current layout relative path.
     */
    
    public function init()
    {
        parent::init();
        Yii::$app->user->enableSession = false;
        //if (!Yii::$service->page->theme->fecshopThemeDir) {
        //    Yii::$service->page->theme->fecshopThemeDir = Yii::getAlias(CConfig::param('appfrontBaseTheme'));
        //}
        //if (!Yii::$service->page->theme->layoutFile) {
        //    Yii::$service->page->theme->layoutFile = CConfig::param('appfrontBaseLayoutName');
        //}
        
        //Yii::$service->page->translate->category = 'appfront';
        
    }
    
    /**
     * get current block
     * you can change $this->blockNamespace.
     */
    
    public function getBlock($blockName = '')
    {
        if (!$blockName) {
            $blockName = $this->action->id;
        }
        if (!$this->blockNamespace) {
            $this->blockNamespace = Yii::$app->controller->module->blockNamespace;
        }
        if (!$this->blockNamespace) {
            throw new \yii\web\HttpException(406, 'blockNamespace is empty , you should config it in module->blockNamespace or controller blockNamespace ');
        }
        $viewId = $this->id;
        $viewId = str_replace('/', '\\', $viewId);
        $relativeFile = '\\'.$this->blockNamespace;
        $relativeFile .= '\\'.$viewId.'\\'.ucfirst($blockName);
        //查找是否在rewriteMap中存在重写
        $relativeFile = Yii::mapGetName($relativeFile);
        
        return new $relativeFile();
    }
    
   
}
