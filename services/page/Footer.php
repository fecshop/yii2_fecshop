<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services\page;
use Yii;
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
use fec\helpers\CSession;
use fec\helpers\CUrl;
use fecshop\services\Service;
/**
 * Breadcrumbs services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Footer extends Service
{
	//public $textTerms;
	const TEXT_TERMS 	= 'footer_text_terms';
	const COPYRIGHT 	= 'footer_copyright';
	const FOLLOW_USE 	= 'footer_follow_us';
	const PAYMENT_IMG 	= 'footer_payment_img';
	
	
	public function getTextTerms(){
		Yii::$service->page->staticblock->get(self::TEXT_TERMS);
	}

	
	public function getCopyRight(){
		Yii::$service->page->staticblock->get(self::COPYRIGHT);
	}
	
	
	public function followUs(){
		Yii::$service->page->staticblock->get(self::FOLLOW_USE);
	}
	
	
	public function getPaymentImg(){
		Yii::$service->page->staticblock->get(self::PAYMENT_IMG);
	}
	
	
	
}
