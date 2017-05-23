<?php
/**
 * FecShop file.
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\cms\staticblock;

/**
 * Cms Article services.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
interface StaticBlockInterface
{
    public function getByPrimaryKey($primaryKey);

    public function coll($filter);

    public function save($one);

    public function remove($ids);
}
