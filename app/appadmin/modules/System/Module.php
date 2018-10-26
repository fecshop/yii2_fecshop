<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\System;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Module extends \fec\AdminModule
{
    public $blockNamespace;

    public function init()
    {
        $nameSpace = __NAMESPACE__;
        // 以下代码必须指定
        // 设置模块 controller namespace的文件路径
        $this->controllerNamespace = $nameSpace . '\\controllers';
        // 设置模块block namespace的文件路径
        $this->blockNamespace = $nameSpace . '\\block';
        // $this->_currentDir = __DIR__;
        // $this->_currentNameSpace = __NAMESPACE__;

        // 指定默认的man文件
        $this->layout = '/main_ajax.php';
        parent::init();
    }
}
