<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\console\modules\Order;

use fecshop\app\console\modules\ConsoleModule;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Module extends ConsoleModule
{
    public $blockNamespace;

    public function init()
    {
        // 以下代码必须指定
        $nameSpace = __NAMESPACE__;
        $this->controllerNamespace = $nameSpace . '\\controllers';
        $this->blockNamespace = $nameSpace . '\\block';
        parent::init();
    }
}
