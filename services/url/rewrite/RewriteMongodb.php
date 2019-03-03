<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\url\rewrite;

//use fecshop\models\mongodb\url\UrlRewrite;
use Yii;
use fecshop\services\Service;
use yii\base\InvalidValueException;

/**
 * Url Rewrite RewriteMongodb service
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class RewriteMongodb extends Service implements RewriteInterface
{
    public $numPerPage = 20;

    protected $_urlRewriteModelName = '\fecshop\models\mongodb\url\UrlRewrite';

    protected $_urlRewriteModel;
    
    public function init()
    {
        parent::init();
        list($this->_urlRewriteModelName, $this->_urlRewriteModel) = \Yii::mapGet($this->_urlRewriteModelName);
    }
    
    /**
     * @param $urlKey | string
     * 通过重写后的urlkey字符串，去url_rewrite表中查询，找到重写前的url字符串。
     */
    public function getOriginUrl($urlKey)
    {
        $UrlData = $this->_urlRewriteModel->find()->where([
            'custom_url_key' => $urlKey,
        ])->asArray()->one();
        if ($UrlData['custom_url_key']) {
            return $UrlData['origin_url'];
        }
    }

    public function getPrimaryKey()
    {
        return '_id';
    }

    public function getByPrimaryKey($primaryKey)
    {
        if ($primaryKey) {
            return $this->_urlRewriteModel->findOne($primaryKey);
        } else {
            return new $this->_urlRewriteModelName();
        }
    }

    /*
     * example filter:
     * [
     * 		'numPerPage' 	=> 20,
     * 		'pageNum'		=> 1,
     * 		'orderBy'	=> ['_id' => SORT_DESC, 'sku' => SORT_ASC ],
            where'			=> [
                ['>','price',1],
                ['<=','price',10]
     * 			['sku' => 'uk10001'],
     * 		],
     * 	'asArray' => true,
     * ]
     */
    public function coll($filter = '')
    {
        $query = $this->_urlRewriteModel->find();
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
    public function save($one)
    {
        $primaryVal = isset($one[$this->getPrimaryKey()]) ? $one[$this->getPrimaryKey()] : '';
        if ($primaryVal) {
            $model = $this->_urlRewriteModel->findOne($primaryVal);
            if (!$model) {
                Yii::$service->helper->errors->add('UrlRewrite {primaryKey} is not exist', ['primaryKey'=>$this->getPrimaryKey()]);

                return;
            }
        } else {
            $model = new $this->_urlRewriteModelName();
        }
        unset($one['_id']);
        $saveStatus = Yii::$service->helper->ar->save($model, $one);

        return true;
    }

    /**
     * @param $ids | Array or String
     * 删除相应的url rewrite 记录
     */
    public function remove($ids)
    {
        if (!$ids) {
            Yii::$service->helper->errors->add('remove id is empty');

            return false;
        }
        if (is_array($ids) && !empty($ids)) {
            foreach ($ids as $id) {
                $model = $this->_urlRewriteModel->findOne($id);
                if (isset($model[$this->getPrimaryKey()]) && !empty($model[$this->getPrimaryKey()])) {
                    $url_key = $model['url_key'];
                    $model->delete();
                } else {
                    //throw new InvalidValueException("ID:$id is not exist.");
                    Yii::$service->helper->errors->add('UrlRewrite Remove Errors:ID {id} is not exist.', ['id' => $id]);

                    return false;
                }
            }
        } else {
            $id = $ids;
            $model = $this->_urlRewriteModel->findOne($id);
            if (isset($model[$this->getPrimaryKey()]) && !empty($model[$this->getPrimaryKey()])) {
                $url_key = $model['url_key'];
                $model->delete();
            } else {
                Yii::$service->helper->errors->add('UrlRewrite Remove Errors:ID:{id} is not exist.', ['id' => $id]);

                return false;
            }
        }

        return true;
    }

    /**
     * @param $time | Int
     * 根据updated_at 更新时间，删除相应的url rewrite 记录
     */
    public function removeByUpdatedAt($time)
    {
        if ($time) {
            $this->_urlRewriteModel->deleteAll([
                '$or' => [
                    [
                        'updated_at' => [
                            '$lt' => (int) $time,
                        ],
                    ],
                    [
                        'updated_at' => [
                            '$exists' => false,
                        ],
                    ],
                ],

            ]);
            echo "delete complete \n";
        }
    }

    /**
     * 返回url rewrite model 对应的query
     */
    public function find()
    {
        return $this->_urlRewriteModel->find();
    }

    /**
     * 返回url rewrite 查询结果
     */
    public function findOne($where)
    {
        return $this->_urlRewriteModel->findOne($where);
    }

    /**
     * 返回url rewrite model
     */
    public function newModel()
    {
        return new $this->_urlRewriteModelName();
    }
}
