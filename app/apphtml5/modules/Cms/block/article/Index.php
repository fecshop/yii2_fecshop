<?php
/*
 * 存放 一些基本的非数据库数据 如 html
 * 都是数组
 */

namespace fecshop\app\apphtml5\modules\cms\block\article;

use fec\helpers\CRequest;
use Yii;

class Index
{
    protected $_artile;
    protected $_title;

    public function getLastData()
    {

        //echo Yii::$service->page->translate->__('fecshop,{username}', ['username' => 'terry']);
        $this->initHead();
        // change current layout File.
        //Yii::$service->page->theme->layoutFile = 'home.php';
        return [
            'title' => $this->_title,
            'content' => Yii::$service->store->getStoreAttrVal($this->_artile['content'], 'content'),
            'created_at' => $this->_artile['created_at'],
        ];
    }

    public function initHead()
    {
        $primaryKey = Yii::$service->cms->article->getPrimaryKey();
        $primaryVal = CRequest::param($primaryKey);
        $article = Yii::$service->cms->article->getByPrimaryKey($primaryVal);
        $this->_artile = $article;

        Yii::$app->view->registerMetaTag([
            'name' => 'keywords',
            'content' => Yii::$service->store->getStoreAttrVal($article['meta_keywords'], 'meta_keywords'),
        ]);

        Yii::$app->view->registerMetaTag([
            'name' => 'description',
            'content' => Yii::$service->store->getStoreAttrVal($article['meta_description'], 'meta_description'),
        ]);
        $this->_title = Yii::$service->store->getStoreAttrVal($article['title'], 'title');
        Yii::$app->view->title = $this->_title;
    }
}
