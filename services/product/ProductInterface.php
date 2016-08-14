<?php
/**
 * FecShop file.
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services\product;
/**
 * Product services interface
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
interface ProductInterface{
	
	public function getByPrimaryKey($primaryKey);
	public function coll($filter);
	public function save($one,$originUrlKey);
	public function remove($ids);
}