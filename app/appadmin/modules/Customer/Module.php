<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Customer;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Module extends \fec\AdminModule
{
    public function init()
    {

        // 以下代码必须指定
        $this->controllerNamespace = __NAMESPACE__ . '\\controllers';
        $this->_currentDir = __DIR__;
        $this->_currentNameSpace = __NAMESPACE__;

        // 指定默认的man文件
        $this->layout = '/main_ajax.php';
        parent::init();
    }
}
