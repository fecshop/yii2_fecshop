<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appinstall\modules\Database;

use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 * Catalog Module 模块
 */
class Module extends \yii\base\Module
{
    public $blockNamespace;
    
    public function init()
    {
        // 以下代码必须指定
        $nameSpace = __NAMESPACE__;
        // 如果 Yii::$app 对象是由类\yii\web\Application 实例化出来的。
        if (Yii::$app instanceof \yii\web\Application) {
            // 设置模块 controller namespace的文件路径
            $this->controllerNamespace = $nameSpace . '\\controllers';
            // 设置模块block namespace的文件路径
            $this->blockNamespace = $nameSpace . '\\block';
        }
        $this->layout = 'main.php';
        parent::init();
    }
}
