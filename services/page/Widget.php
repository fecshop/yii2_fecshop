<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\page;

use fec\helpers\CCache;
use fecshop\interfaces\block\BlockCache;
use fecshop\services\Service;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\InvalidValueException;

/**
 * Page widget services.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Widget extends Service
{
    public $defaultObMethod = 'getLastData';
    public $widgetConfig;

    /**
     * @property configKey   String or Array
     * 如果传递的是一个配置数组，内容格式如下：
     * [
     *    # class 选填
     *    'class' => 'fec\block\TestMenu',
     *    # view 为 必填 ， view可以用两种方式
     *    #  view 1 使用绝对地址的方式
     *    'view'  => '@fec/views/testmenu/index.php',
     *        OR
     *    #  view 2 使用相对地址，通过当前模板进行查找
     *    'view'  => 'cms/home/index.php',
     *
     *    # 下面为选填
     *    'method'=> 'getLastData',
     *    'terry1'=> 'My1',
     *   'terry2'=> 'My2',
     * ]
     * 如果传递的是字符串，那么会去配置（$widgetConfig）中查找
     * 最后找到后，通过renderContent函数，得到html
     * 该功能大致为通过一个动态数据提供者block，和内容显示部分view，view里面需要使用的动态变量
     * 由block提供，最终生成一个html区块，返回。
     */
    protected function actionRender($configKey, $parentThis = '')
    {
        $config = '';
        if (is_array($configKey)) {
            $config = $configKey;
            $configKey = '';
        } else {
            if (isset($this->widgetConfig[$configKey])) {
                $config = $this->widgetConfig[$configKey];
            } else {
                throw new InvalidValueException(" config key: '$configKey', can not find in  ".'Yii::$service->page->widget->widgetConfig'.', you must config it before use it.');
            }
        }

        return $this->renderContent($configKey, $config, $parentThis);
    }
    /**
     * @property $configKey | string ,使用配置中的widget，该参数对应相应的数组key
     * @property $config,就是上面actionRender()方法中的参数，格式一样。
     * @property $parentThis | array or '' , 调用层传递的参数数组，可以在view中调用。
     *
     */
    protected function actionRenderContentHtml($configKey, $config, $parentThis = '')
    {
        if (!isset($config['view']) || empty($config['view'])
        ) {
            throw new InvalidConfigException('view and class must exist in array config!');
        }
        $params = [];
        $view = $config['view'];
        unset($config['view']);
        $viewFile = $this->getViewFile($view);
        if (!isset($config['class']) || empty($config['class'])) {
            if ($parentThis) {
                $params['parentThis'] = $parentThis;
            }

            return Yii::$app->view->renderFile($viewFile, $params);
        }
        if (isset($config['method']) && !empty($config['method'])) {
            $method = $config['method'];
            unset($config['method']);
        } else {
            $method = $this->defaultObMethod;
        }
        $ob = Yii::createObject($config);
        $params = $ob->$method();
        if ($parentThis) {
            $params['parentThis'] = $parentThis;
        }

        return Yii::$app->view->renderFile($viewFile, $params);
    }
    /**
     * @property $configKey | string ,使用配置中的widget，该参数对应相应的数组key
     * @property $config,就是上面actionRender()方法中的参数，格式一样。
     * @property $parentThis | array or '' , 调用层传递的参数数组，可以在view中调用。
     *
     */
    protected function actionRenderContent($configKey, $config, $parentThis = '')
    {
        if (isset($config['cache']['enable']) && $config['cache']['enable']) {
            if (!isset($config['class']) || !$config['class']) {
                throw new InvalidConfigException('in widget ['.$configKey.'],you enable cache ,you must config widget class .');
            } elseif ($ob = new $config['class']()) {
                if ($ob instanceof BlockCache) {
                    $cacheKey = $ob->getCacheKey();
                    if (!($content = CCache::get($cacheKey))) {
                        $cache = $config['cache'];
                        $timeout = isset($cache['timeout']) ? $cache['timeout'] : 0;
                        unset($config['cache']);
                        $content = $this->renderContentHtml($configKey, $config, $parentThis);
                        CCache::set($cacheKey, $content, $timeout);
                    }

                    return $content;
                } else {
                    throw new InvalidConfigException($config['class'].' must implete fecshop\interfaces\block\BlockCache  when you use block cache .');
                }
            }
        }
        $content = $this->renderContentHtml($configKey, $config, $parentThis);

        return $content;
    }

    /**
     * find theme file by mutil theme ,if not find view file  and $throwError=true, it will throw InvalidValueException.
     */
    protected function getViewFile($view, $throwError = true)
    {
        $view = trim($view);
        if (substr($view, 0, 1) == '@') {
            return Yii::getAlias($view);
        }
        $absoluteDir = Yii::$service->page->theme->getThemeDirArr();

        foreach ($absoluteDir as $dir) {
            if ($dir) {
                $file = $dir.'/'.$view;
                //echo $file."<br/>";
                if (file_exists($file)) {
                    return $file;
                }
            }
        }

        /* not find view file */
        if ($throwError) {
            $notExistFile = [];
            foreach ($absoluteDir as $dir) {
                if ($dir) {
                    $file = $dir.'/'.$view;
                    $notExistFile[] = $file;
                }
            }
            throw new InvalidValueException('view file is not exist in'.implode(',', $notExistFile));
        } else {
            return false;
        }
    }
}
