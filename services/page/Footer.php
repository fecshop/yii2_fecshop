<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\page;

use fecshop\services\Service;
use Yii;

/**
 * page Footer services.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Footer extends Service
{
    //public $textTerms;
    const TEXT_TERMS    = 'footer_text_terms';

    const COPYRIGHT     = 'footer_copyright';

    const FOLLOW_USE    = 'footer_follow_us';

    const PAYMENT_IMG   = 'footer_payment_img';

    /**
     * 得到页面底部的html部分
     */
    public function getTextTerms()
    {
        Yii::$service->page->staticblock->get(self::TEXT_TERMS);
    }

    /**
     * 得到页面底部的版权部分
     */
    public function getCopyRight()
    {
        Yii::$service->page->staticblock->get(self::COPYRIGHT);
    }

    /**
     * 得到页面底部的follow us部分
     */
    public function followUs()
    {
        Yii::$service->page->staticblock->get(self::FOLLOW_USE);
    }

    /**
     * 得到页面底部的支付图片部分
     */
    public function getPaymentImg()
    {
        Yii::$service->page->staticblock->get(self::PAYMENT_IMG);
    }
}
