<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services\helper;
use Yii;
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
use fec\helpers\CSession;
use fec\helpers\CUrl;
use fecshop\services\Service;
/**
 * Helper Errors services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Errors extends Service
{
	protected $_errors = false ;
	public $status = true;
	
	public function add($errros){
		if($errros){
			$this->_errors[] = $errros;
		}
	}
	
	public function get($arrayFormat=false){
		if($this->_errors){
			$errors = $this->_errors;
			$this->_errors = false;
			if(!$arrayFormat){
				return implode('|',$errors);
			}else{
				return $errors;
			}
			
		}
		return false;
	}
	
	
}