<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\email\widgets\customer\contacts;

use fecshop\services\email\widgets\BodyBase;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Body extends BodyBase
{
    public function getLastData()
    {
        $identity = $this->params;
        return [
            'email'     => $identity['email'],
            'name'      => $identity['name'],
            'telephone' => $identity['telephone'],
            'comment'   => $identity['comment'],
            'store'     => Yii::$service->store->currentStore,
            'identity'  => $identity,
        ];
    }
}
