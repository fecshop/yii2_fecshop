<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\customer\newsletter;

//use fecshop\models\mongodb\customer\Newsletter as MongoNewsletter;
use fecshop\services\Service;
use Yii;

/**
 * Page Newsletter services.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class NewsletterMongodb extends Service implements NewsletterInterface
{
    public $numPerPage = 20;

    protected $_newsletterModelName = '\fecshop\models\mongodb\customer\Newsletter';

    protected $_newsletterModel;
    
    public function init()
    {
        parent::init();
        list($this->_newsletterModelName, $this->_newsletterModel) = Yii::mapGet($this->_newsletterModelName);
    }

    public function getPrimaryKey()
    {
        return '_id';
    }

    public function getByPrimaryKey($primaryKey)
    {
        if ($primaryKey) {
            return $this->_newsletterModel->findOne($primaryKey);
        } else {
            return new $this->_newsletterModelName();
        }
    }

    /*
     * example filter:
     * [
     * 		'numPerPage' 	=> 20,
     * 		'pageNum'		=> 1,
     * 		'orderBy'	=> ['_id' => SORT_DESC, 'sku' => SORT_ASC ],
            'where'			=> [
                ['>','price',1],
                ['<=','price',10]
     * 			['sku' => 'uk10001'],
     * 		],
     * 	'asArray' => true,
     * ]
     */
    public function coll($filter = '')
    {
        $query = $this->_newsletterModel->find();
        $query = Yii::$service->helper->ar->getCollByFilter($query, $filter);

        return [
            'coll' => $query->all(),
            'count'=> $query->limit(null)->offset(null)->count(),
        ];
    }

    /**
     * @param $emailAddress | String
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
     * @param $emailAddress | String
     * @return bool
     *              订阅邮件
     */
    protected function actionSubscribe($emailAddress, $isRegister = false)
    {
        if (!$emailAddress) {
            Yii::$service->helper->errors->add('newsletter email address is empty');

            return;
        } elseif (!Yii::$service->email->validateFormat($emailAddress)) {
            Yii::$service->helper->errors->add('The email address format is incorrect!');

            return;
        } elseif ($this->emailIsExist($emailAddress)) {
            if ($isRegister) {
                return true;
            } else {
                Yii::$service->helper->errors->add('ERROR,Your email address has subscribe , Please do not repeat the subscription');

                return;
            }
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
