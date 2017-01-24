<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\interfaces\block;
/**
 * Interface BlockCache
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
interface BlockCache{
	const BLOCK_CACHE_PREFIX = 'block_cache';
	public function getCacheKey();
	
}