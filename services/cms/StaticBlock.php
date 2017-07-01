<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\cms;

use fecshop\services\cms\staticblock\StaticBlockMongodb;
use fecshop\services\cms\staticblock\StaticBlockMysqldb;
use fecshop\services\Service;
use Yii;

/**
 * Cms StaticBlock services. 静态块部分，譬如首页的某个区块，类似走马灯图，广告图等经常需要改动的部分，可以在后台进行改动。
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Staticblock extends Service
{
    public $storage = 'mongodb';
    protected $_static_block;

    /**
     * init static block db.
     */
    public function init()
    {
        if ($this->storage == 'mongodb') {
            $this->_static_block = new StaticBlockMongodb();
        } elseif ($this->storage == 'mysqldb') {
            $this->_static_block = new StaticBlockMysqldb();
        }
    }

    /**
     * get store static block content by identify
     * example <?=  Yii::$service->cms->staticblock->getStoreContentByIdentify('home-big-img','appfront') ?>.
     */
    protected function actionGetStoreContentByIdentify($identify, $app = 'common')
    {
        $staticBlock    = $this->_static_block->getByIdentify($identify);
        $content        = $staticBlock['content'];
        $storeContent   = Yii::$service->store->getStoreAttrVal($content, 'content');
        $_params_       = $this->getStaticBlockVariableArr($app);
        ob_start();
        ob_implicit_flush(false);
        extract($_params_, EXTR_OVERWRITE);
        foreach ($_params_ as $k => $v) {
            $key = '{{'.$k.'}}';
            if (strstr($storeContent, $key)) {
                $storeContent = str_replace($key, $v, $storeContent);
            }
        }
        echo $storeContent;

        return ob_get_clean();
    }

    /**
     * staticblock中的变量，可以通过{{homeUlr}},来获取下面的值。
     */
    protected function getStaticBlockVariableArr($app)
    {
        return [
            'homeUrl'   => Yii::$service->url->homeUrl(),
            'imgBaseUrl'=> Yii::$service->image->getBaseImgUrl($app),
        ];
    }

    /**
     * get artile's primary key.
     */
    protected function actionGetPrimaryKey()
    {
        return $this->_static_block->getPrimaryKey();
    }

    /**
     * get artile model by primary key.
     */
    protected function actionGetByPrimaryKey($primaryKey)
    {
        return $this->_static_block->getByPrimaryKey($primaryKey);
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
        return $this->_static_block->coll($filter);
    }

    /**
     * @property $one|array , save one data .
     * save $data to cms model,then,add url rewrite info to system service urlrewrite.
     */
    protected function actionSave($one)
    {
        return $this->_static_block->save($one);
    }

    protected function actionRemove($ids)
    {
        return $this->_static_block->remove($ids);
    }
}
