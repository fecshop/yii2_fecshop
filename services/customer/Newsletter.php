<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\customer;

//use fecshop\models\mongodb\customer\Newsletter as NewsletterModel;
use fecshop\services\Service;
use Yii;

/**
 * Newsletter child services.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Newsletter extends Service
{
    protected $_newsletterModelName = '\fecshop\models\mongodb\customer\Newsletter';
    protected $_newsletterModel;
    
    public function __construct(){
        list($this->_newsletterModelName,$this->_newsletterModel) = Yii::mapGet($this->_newsletterModelName);  
    }
    /**
     * @property $emailAddress | String
     * @return bool
     *              检查邮件是否之前被订阅过
     */
    protected function emailIsExist($emailAddress)
    {
        $primaryKey = $this->_newsletterModel->primaryKey();
        $one = $this->_newsletterModel->findOne(['email' => $emailAddress]);
        if ($one[$primaryKey]) {
            return true;
        }

        return false;
    }

    /**
     * @property $emailAddress | String
     * @return bool
     *              订阅邮件
     */
    protected function actionSubscribe($emailAddress)
    {
        if (!$emailAddress) {
            Yii::$service->helper->errors->add('newsletter email address is empty');

            return;
        } elseif (!Yii::$service->email->validateFormat($emailAddress)) {
            Yii::$service->helper->errors->add('The email address format is incorrect!');

            return;
        } elseif ($this->emailIsExist($emailAddress)) {
            Yii::$service->helper->errors->add('ERROR,Your email address has subscribe , Please do not repeat the subscription');

            return;
        }
        $model = $this->_newsletterModel;
        $newsletterModel = new $this->_newsletterModelName();
        $newsletterModel->email = $emailAddress;
        $newsletterModel->created_at = time();
        $newsletterModel->status = $model::ENABLE_STATUS;
        $newsletterModel->save();

        return true;
    }
}
