<?php
/**
 * FecShop file.
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\components;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\Component;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Store extends Component implements BootstrapInterface
{
    public $appName;

    public function bootstrap($app)
    {
        if ($this->appName == 'appadmin') {
            Yii::$service->admin->bootstrap($app);
        } else {
            Yii::$service->store->bootstrap($app);
        }
    }
}
