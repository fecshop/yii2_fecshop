<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\apphtml5\modules;

use fec\controllers\FecController;
use fec\helpers\CConfig;
use Yii;
use yii\base\InvalidValueException;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 * Apphtml5 入口的controller的基类
 */
class AppfrontController extends FecController
{
    public $blockNamespace;

    /**
     * init theme component property : $fecshopThemeDir and $layoutFile
     * $fecshopThemeDir is apphtml5 base theme directory.
     * layoutFile is current layout relative path.
     */
    public function init()
    {
        /**
         * 如果模板路径没有配置，则配置模板路径
         */
        if (!Yii::$service->page->theme->fecshopThemeDir) {
            Yii::$service->page->theme->fecshopThemeDir = Yii::getAlias(CConfig::param('apphtml5BaseTheme'));
        }
        /**
         * 如果layout文件没有配置，则配置layout文件
         */
        if (!Yii::$service->page->theme->layoutFile) {
            Yii::$service->page->theme->layoutFile = CConfig::param('apphtml5BaseLayoutName');
        }
        /*
         *  set i18n translate category.
         */
        Yii::$service->page->translate->category = 'apphtml5';
        /*
         * 自定义Yii::$classMap,用于重写
         */
    }

    /**
     * @property $blockName | String
     * get current block
     * 这个函数的controller中得到block文件，譬如：
     * cms模块的ArticleController的actinIndex()方法中使用$this->getBlock()->getLastData()方法，
     * 对应的是cms/block/article/Index.php里面的getLastData()，
     * 也就是说，这个block文件路径和controller的路径有一定的对应关系
     * 这个思想来自于magento的block。
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

    /**
     * @property $view|string , (only) view file name ,by this module id, this controller id , generate view relative path.
     * @property $params|Array,
     * 这个是fecshop重写的render函数，根据fecshop的多模板机制
     * 首先在高级别的模板中找view文件，如果找不到，按照模板路径优先级依次查找
     * 直到找到view'文件。
     * 1.get exist view file from mutil theme by theme protity.
     * 2.get content by yii view compontent  function renderFile()  ,
     */
    public function render($view, $params = [])
    {
        $viewFile = Yii::$service->page->theme->getViewFile($view);
        $content = Yii::$app->view->renderFile($viewFile, $params, $this);

        return $this->renderContent($content);
    }
    /**
     * @property $view|string 
     * Get current layoutFile absolute path from mutil theme dir by protity.
     * 首先在高级别的模板中找view文件，如果找不到，按照模板路径优先级依次查找
     * 直到找到view'文件。
     */
    public function findLayoutFile($view)
    {
        $layoutFile = '';
        $relativeFile = 'layouts/'.Yii::$service->page->theme->layoutFile;
        $absoluteDir = Yii::$service->page->theme->getThemeDirArr();
        foreach ($absoluteDir as $dir) {
            if ($dir) {
                $file = $dir.'/'.$relativeFile;
                if (file_exists($file)) {
                    $layoutFile = $file;

                    return $layoutFile;
                }
            }
        }
        throw new InvalidValueException('layout file is not exist!');
    }
}
