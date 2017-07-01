<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\cms;

use fecshop\services\cms\article\ArticleMongodb;
use fecshop\services\cms\article\ArticleMysqldb;
use fecshop\services\Service;
use Yii;

/**
 * Cms Article services. 文字条款类的page单页。
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Article extends Service
{
    public $storage = 'mongodb';
    protected $_article;

    public function init()
    {
        if ($this->storage == 'mongodb') {
            $this->_article = new ArticleMongodb();
        } elseif ($this->storage == 'mysqldb') {
            $this->_article = new ArticleMysqldb();
        }
    }

    /**
     * Get Url by article's url key.
     */
    //public function getUrlByPath($urlPath){
        //return Yii::$service->url->getHttpBaseUrl().'/'.$urlKey;
        //return Yii::$service->url->getUrlByPath($urlPath);
    //}

    /**
     * get artile's primary key.
     */
    protected function actionGetPrimaryKey()
    {
        return $this->_article->getPrimaryKey();
    }

    /**
     * get artile model by primary key.
     */
    protected function actionGetByPrimaryKey($primaryKey)
    {
        return $this->_article->getByPrimaryKey($primaryKey);
    }

    /**
     * 得到category model的全名.
     */
    protected function actionGetModelName()
    {
        return get_class($this->_article->getByPrimaryKey());
    }

    /**
     * @property $filter|array
     * get artile collection by $filter
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
    protected function actionColl($filter = '')
    {
        return $this->_article->coll($filter);
    }

    /**
     * @property $one|array , save one data .
     * @property $originUrlKey|string , article origin url key.
     * save $data to cms model,then,add url rewrite info to system service urlrewrite.
     */
    protected function actionSave($one, $originUrlKey)
    {
        return $this->_article->save($one, $originUrlKey);
    }

    protected function actionRemove($ids)
    {
        return $this->_article->remove($ids);
    }
}
