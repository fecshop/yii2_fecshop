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
use fec\helpers\CRequest;
use fecshop\services\Service;
use fecshop\models\mongodb\FecshopServiceLog;
/**
 * AR services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Log extends Service
{
	public $log_config;
	protected $_serviceContent;
	protected $_serviceUid;
	protected $_isServiceLog;
	protected $_isServiceLogDbPrint;
	protected $_isServiceLogHtmlPrint;
	protected $_isServiceLogDbPrintByParam;
	
	
	
	/**
	 * Log：get log uuid .
	 */
	public function getLogUid(){
		if(!$this->_serviceUid){
			$this->_serviceUid = $this->guid();
		}
		return $this->_serviceUid;
	}
	
	
	
	/**
	 * ServiceLog：是否开启service log
	 */
	public function isServiceLogEnable(){
		if($this->_isServiceLog === null){
			if(
				isset($this->log_config['services']['enable'])
			&&	$this->log_config['services']['enable']
			){
				$this->_isServiceLog = true;
			}else{
				$this->_isServiceLog = false;
			}
		}
		return $this->_isServiceLog;
	}
	
	/**
	 * ServiceLog：保存serviceLog
	 */
	public function printServiceLog($log_info){
		if($this->isServiceLogDbPrint()){
			FecshopServiceLog::getCollection()->save($log_info);
		}
		if($this->isServiceLogHtmlPrint() || $this->isServiceLogDbPrintByParam()){
			$str = '<br>#################################<br><table>';
			foreach($log_info as $k=>$v){
				if(is_array($v)){
					$v = implode('<br>',$v);
					$str .= "<tr>
					<td>$k</td><td>$v</td>
					</tr>";
				}else{
					$str .= "<tr>
					<td>$k</td><td>$v</td>
					</tr>";
				}
			}
			$str .= '</table><br>#################################<br><br>';
			echo $str ;
		}
	}
	
	/**
	 * ServiceLog：if service log db print is enable.
	 */
	protected function isServiceLogDbPrint(){
		if($this->_isServiceLogDbPrint === null){
			if(
				isset($this->log_config['services']['enable'])
			&&	$this->log_config['services']['enable']
			&&	isset($this->log_config['services']['dbprint'])
			&&	$this->log_config['services']['dbprint']
			){
				$this->_isServiceLogDbPrint = true;
			}else{
				$this->_isServiceLogDbPrint = false;
			}
		}
		return $this->_isServiceLogDbPrint;
		
	}
	/**
	 * ServiceLog：在前台打印servicelog是否开启
	 */
	protected function isServiceLogHtmlPrint(){
		if($this->_isServiceLogHtmlPrint === null){
			if(
				isset($this->log_config['services']['enable'])
			&&	$this->log_config['services']['enable']
			&&	isset($this->log_config['services']['htmlprint'])
			&&	$this->log_config['services']['htmlprint']
			){
				$this->_isServiceLogHtmlPrint = true;
			}else{
				$this->_isServiceLogHtmlPrint = false;
			}
		}
		return $this->_isServiceLogHtmlPrint;
	}
	
	/**
	 * ServiceLog：通过参数，在前台打印servicelog是否开启
	 */
	protected function isServiceLogDbPrintByParam(){
		if($this->_isServiceLogDbPrintByParam === null){
			$this->_isServiceLogDbPrintByParam = false;
			if(
				isset($this->log_config['services']['enable'])
			&&	$this->log_config['services']['enable']
			&&	isset($this->log_config['services']['htmlprintbyparam']['enable'])
			&&	$this->log_config['services']['htmlprintbyparam']['enable']
			&&	isset($this->log_config['services']['htmlprintbyparam']['paramVal'])
			&&	($paramVal = $this->log_config['services']['htmlprintbyparam']['paramVal'])
			&&	isset($this->log_config['services']['htmlprintbyparam']['paramKey'])
			&&	($paramKey = $this->log_config['services']['htmlprintbyparam']['paramKey'])
			
			){
				if(CRequest::param($paramKey) == $paramVal){
					$this->_isServiceLogDbPrintByParam = true;
				}
			}
		}
		return $this->_isServiceLogDbPrintByParam;
	}
	
	
	/**
	 * generate  uuid .
	 */
	protected function guid(){
		if (function_exists('com_create_guid')){
			return com_create_guid();
		}else{
			mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
			$charid = strtoupper(md5(uniqid(rand(), true)));
			$hyphen = chr(45);// "-"
			$uuid 	= //chr(123)// "{"
				 substr($charid, 0, 8).$hyphen
				.substr($charid, 8, 4).$hyphen
				.substr($charid,12, 4).$hyphen
				.substr($charid,16, 4).$hyphen
				.substr($charid,20,12)
				//.chr(125)// "}"
				;
			return $uuid;
		}
	}


	
	
	
	
}