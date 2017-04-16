<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services;
use Yii;
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
/**
 * Cart services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Cache extends Service
{
	# 各个页面cache的配置
	public $cacheConfig;
	# cache 总开关
	public $enable;
	
	/**
	 * @property $cacheKey | String , 具体的缓存名字，譬如 product  category 
	 * @return boolean, 如果enable为true，则返回为true
	 */
	public function isEnable($cacheKey){
		if($this->enable && isset($this->cacheConfig[$cacheKey]['enable'])){
			return $this->cacheConfig[$cacheKey]['enable'];
		}else{
			return false;
		}
	}
	
	
	/**
	 * @property $cacheKey | String , 具体的缓存名字，譬如 product  category 
	 * @return int, 如果enable为true，则返回为true
	 */
	public function timeout($cacheKey){
		if(isset($this->cacheConfig[$cacheKey]['timeout'])){
			return $this->cacheConfig[$cacheKey]['timeout'];
		}else{
			return 0;
		}
	}
	
	
	/**
	 * @property $cacheKey | String , 具体的缓存名字，譬如 product  category 
	 * @return string, 如果enable为true，则返回为true
	 */
	public function disableUrlParam($cacheKey){
		if(isset($this->cacheConfig[$cacheKey]['disableUrlParam'])){
			return $this->cacheConfig[$cacheKey]['disableUrlParam'];
		}else{
			return '';
		}
	}
	
	/**
	 * @property $cacheKey | String , 具体的缓存名字，譬如 product  category 
	 * @return string, 如果enable为true，则返回为true
	 * url的参数，哪一些参数作为缓存唯一的依据，譬如p（分页的值）
	 * 
	 */
	public function cacheUrlParam($cacheKey){
		if(isset($this->cacheConfig[$cacheKey]['cacheUrlParam'])){
			return $this->cacheConfig[$cacheKey]['cacheUrlParam'];
		}else{
			return '';
		}
	}
	
	
	
	
	
	
	
}