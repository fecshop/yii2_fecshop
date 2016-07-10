<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services\cms\article;

interface ArticleInterface{
	
	public function getById($id);
	public function coll($filter);
	public function save($one);
	public function remove($ids);
}