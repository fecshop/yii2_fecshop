<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\cms\article;

//use fecshop\models\mongodb\cms\Article;
use Yii;
use yii\base\InvalidValueException;
use fecshop\services\Service;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class ArticleMongodb extends Service implements ArticleInterface
{
    public $numPerPage = 20;

    protected $_articleModelName = '\fecshop\models\mongodb\cms\Article';

    protected $_articleModel;
    
    public function init()
    {
        parent::init();
        list($this->_articleModelName, $this->_articleModel) = Yii::mapGet($this->_articleModelName);
    }
    
    public function getPrimaryKey()
    {
        return '_id';
    }

    public function getByPrimaryKey($primaryKey)
    {
        if ($primaryKey) {
            return $this->_articleModel->findOne($primaryKey);
        } else {
            return new $this->_articleModelName;
        }
    }

    /**
     * @param $urlKey | String ,  对应表的url_key字段
     * 根据url_key 查询得到article model
     */
    public function getByUrlKey($urlKey)
    {
        if ($urlKey) {
            $model = $this->_articleModel->findOne(['url_key' => '/'.$urlKey]);
            if (isset($model['url_key'])) {
                return $model;
            }
        }
        return false;
    }

    /*
     * example filter:
     * [
     * 		'numPerPage' 	=> 20,
     * 		'pageNum'		=> 1,
     * 		'orderBy'	=> ['_id' => SORT_DESC, 'sku' => SORT_ASC ],
     * 		'where'			=> [
                ['>','price',1],
                ['<=','price',10]
     * 			['sku' => 'uk10001'],
     * 		],
     * 	'asArray' => true,
     * ]
     */
    public function coll($filter = '')
    {
        $query = $this->_articleModel->find();
        $query = Yii::$service->helper->ar->getCollByFilter($query, $filter);

        return [
            'coll' => $query->all(),
            'count'=> $query->limit(null)->offset(null)->count(),
        ];
    }

    /**
     * @param $one|array
     * save $data to cms model,then,add url rewrite info to system service urlrewrite.
     */
    public function save($one, $originUrlKey)
    {
        $currentDateTime = \fec\helpers\CDate::getCurrentDateTime();
        $primaryVal = isset($one[$this->getPrimaryKey()]) ? $one[$this->getPrimaryKey()] : '';
        if ($primaryVal) {
            $model = $this->_articleModel->findOne($primaryVal);
            if (!$model) {
                Yii::$service->helper->errors->add('article {primaryKey} is not exist' , ['primaryKey' => $this->getPrimaryKey()]);

                return;
            }
        } else {
            $model = new $this->_articleModelName;
            $model->created_at = time();
            $model->created_user_id = \fec\helpers\CUser::getCurrentUserId();
            $primaryVal = new \MongoDB\BSON\ObjectId();
            $model->{$this->getPrimaryKey()} = $primaryVal;
        }
        $model->updated_at = time();
        unset($one['_id']);
        $saveStatus         = Yii::$service->helper->ar->save($model, $one);
        $originUrl          = $originUrlKey.'?'.$this->getPrimaryKey() .'='. $primaryVal;
        $originUrlKey       = isset($one['url_key']) ? $one['url_key'] : '';
        $defaultLangTitle   = Yii::$service->fecshoplang->getDefaultLangAttrVal($one['title'], 'title');
        $urlKey             = Yii::$service->url->saveRewriteUrlKeyByStr($defaultLangTitle, $originUrl, $originUrlKey);
        $model->url_key = $urlKey;
        $this->initStatus($model);
        $model->save();
        $model['_id'] = (string)$model['_id'];
        return $model->attributes;
    }
    
    protected function initStatus($model)
    {
        $statusArr = [$model::STATUS_ACTIVE, $model::STATUS_DELETED];
        if ($model['status']) {
            $model['status'] = (int) $model['status'];
            if (!in_array($model['status'], $statusArr)) {
                $model['status'] = $model::STATUS_ACTIVE;
            }
        } else {
            $model['status'] = $model::STATUS_ACTIVE;
        }
    }

    /**
     * remove article.
     */
    public function remove($ids)
    {
        if (!$ids) {
            Yii::$service->helper->errors->add('remove id is empty');

            return false;
        }
        if (is_array($ids) && !empty($ids)) {
            $deleteAll = true;
            foreach ($ids as $id) {
                $model = $this->_articleModel->findOne($id);
                if (isset($model[$this->getPrimaryKey()]) && !empty($model[$this->getPrimaryKey()])) {
                    $url_key = $model['url_key'];
                    Yii::$service->url->removeRewriteUrlKey($url_key);
                    $model->delete();
                } else {
                    //throw new InvalidValueException("ID:$id is not exist.");
                    Yii::$service->helper->errors->add('Article Remove Errors:ID {id} is not exist.', ['id' => $id]);
                    $deleteAll = false;
                }
            }
            return $deleteAll;
        } else {
            $id = $ids;
            $model = $this->_articleModel->findOne($id);
            if (isset($model[$this->getPrimaryKey()]) && !empty($model[$this->getPrimaryKey()])) {
                $url_key = $model['url_key'];
                Yii::$service->url->removeRewriteUrlKey($url_key);
                $model->delete();
            } else {
                Yii::$service->helper->errors->add('Article Remove Errors:ID:{id} is not exist', ['id' => $id]);

                return false;
            }
        }

        return true;
    }
}
