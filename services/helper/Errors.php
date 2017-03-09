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
	
	/**
	 * @property $errros | String , 错误信息
	 * @property $arr | Array 变量替换对应的数组
	 * Yii::$service->helper->errors->add('Hello, {username}!', ['username' => $username])
	 */
	public function add($errros,$arr = []){
		if($errros){
			$errros = Yii::$service->page->translate->__($errros,$arr);
			$this->_errors[] = $errros;
		}
	}
	
	public function addByModelErrors($model_errors){
		$error_arr = [];
		if(is_array($model_errors)){
			foreach($model_errors as $errors){
				$arr = [];
				
				foreach($errors as $s){
					$arr[] = Yii::$service->page->translate->__($s);
				}
				$error_arr[]= implode(',',$arr);
			}
			if(!empty($error_arr)){
				$this->_errors[] = implode(',',$error_arr);
			}
		}
	}
	/**
	 * @property $separator 如果是false，则返回数组，
	 *						如果是true则返回用| 分隔的字符串
	 *						如果是传递的分隔符的值，譬如“,”，则返回用这个分隔符分隔的字符串
	 *
	 */
	public function get($separator=false){
		if($this->_errors){
			$errors = $this->_errors;
			$this->_errors = false;
			if(!$separator){
				if($separator === true){
					$separator = '|';
				}
				return implode($separator,$errors);
			}else{
				return $errors;
			}
			
		}
		return false;
	}
	
	
}