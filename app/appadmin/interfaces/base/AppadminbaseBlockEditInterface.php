<?php
namespace fecshop\app\appadmin\interfaces\base;

interface AppadminbaseBlockEditInterface{
	
	/**
	 * set Service ,like $this->_service 	= Yii::$service->cms->article;
	 */
	public function setService();
	/**
	 * config edit array
	 */
	public function getEditArr();
}