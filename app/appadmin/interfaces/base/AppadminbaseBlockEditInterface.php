<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\interfaces\base;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
interface AppadminbaseBlockEditInterface
{
    /**
     * set Service ,like $this->_service 	= Yii::$service->cms->article;.
     */
    public function setService();

    /**
     * config edit array.
     */
    public function getEditArr();
}
