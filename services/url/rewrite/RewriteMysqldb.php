<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\url\rewrite;

//use fecshop\models\mysqldb\url\UrlRewrite;
use Yii;
use fecshop\services\Service;
use yii\base\InvalidValueException;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class RewriteMysqldb extends Service implements RewriteInterface
{
    public $numPerPage = 20;

    /**
     *  language attribute.
     */
    protected $_lang_attr = [

    ];
    
    protected $_urlRewriteModelName = '\fecshop\models\mysqldb\url\UrlRewrite';

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
        return 'id';
    }

    public function getByPrimaryKey($primaryKey)
    {
        if ($primaryKey) {
            $one = $this->_urlRewriteModel->findOne($primaryKey);
            if (!empty($this->_lang_attr)) {
                foreach ($this->_lang_attr as $attrName) {
                    if (isset($one[$attrName])) {
                        $one[$attrName] = unserialize($one[$attrName]);
                    }
                }
            }

            return $one;
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
     * 		where'			=> [
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
        $coll = $query->all();
        if (!empty($coll)) {
            foreach ($coll as $k => $one) {
                if (!empty($this->_lang_attr)) {
                    foreach ($this->_lang_attr as $attr) {
                        $one[$attr] = $one[$attr] ? unserialize($one[$attr]) : '';
                    }
                }
                $coll[$k] = $one;
            }
        }
        //var_dump($one);
        return [
            'coll' => $coll,
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
     * @param $ids | Array or Int
     * 删除相应的url rewrite 记录
     */
    public function remove($ids)
    {
        if (!$ids) {
            Yii::$service->helper->errors->add('remove id is empty');

            return false;
        }
        if (is_array($ids) && !empty($ids)) {
            $innerTransaction = Yii::$service->db->beginTransaction();
            try {
                foreach ($ids as $id) {
                    $model = $this->_urlRewriteModel->findOne($id);
                    if (isset($model[$this->getPrimaryKey()]) && !empty($model[$this->getPrimaryKey()])) {
                        $url_key = $model['url_key'];
                        $model->delete();
                    } else {

                        //throw new InvalidValueException("ID:$id is not exist.");
                        Yii::$service->helper->errors->add('UrlRewrite Remove Errors:ID {id} is not exist.', ['id' => $id]);
                        $innerTransaction->rollBack();

                        return false;
                    }
                }
                $innerTransaction->commit();
            } catch (\Exception $e) {
                Yii::$service->helper->errors->add('UrlRewrite Remove Errors: transaction rollback');
                $innerTransaction->rollBack();

                return false;
            }
        } else {
            $id = $ids;
            $model = $this->_urlRewriteModel->findOne($id);
            if (isset($model[$this->getPrimaryKey()]) && !empty($model[$this->getPrimaryKey()])) {
                $innerTransaction = Yii::$service->db->beginTransaction();
                try {
                    $url_key = $model['url_key'];
                    $model->delete();
                    $innerTransaction->commit();
                } catch (\Exception $e) {
                    Yii::$service->helper->errors->add('UrlRewrite Remove Errors: transaction rollback');
                    $innerTransaction->rollBack();
                }
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
                '<', 'updated_at', $time,
            ]);
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
