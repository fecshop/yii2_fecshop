<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\product;

//use fecshop\models\mongodb\product\Review as ReviewModel;
use fecshop\services\Service;
use Yii;
use yii\base\InvalidValueException;

/**
 * Product Review Service
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Review extends Service
{
    public $filterByLang;
    protected $_reviewModelName = '\fecshop\models\mongodb\product\Review';
    protected $_reviewModel;
    
    public function __construct(){
        list($this->_reviewModelName,$this->_reviewModel) = \Yii::mapGet($this->_reviewModelName);  
    }
    /**
     * 得到review noactive status，默认状态
     */
    protected function actionNoActiveStatus()
    {
        $model = $this->_reviewModel;
        return $model::NOACTIVE_STATUS;
    }

    /**
     * 得到review active status 审核通过的状态
     */
    protected function actionActiveStatus()
    {
        $model = $this->_reviewModel;
        return $model::ACTIVE_STATUS;
    }

    /**
     * 得到review refuse status 审核拒绝的状态
     */
    protected function actionRefuseStatus()
    {
        $model = $this->_reviewModel;
        return $model::REFUSE_STATUS;
    }

    /**
     * @property $arr | Array
     * 初始化review model的属性，因为每一个产品的可能添加的评论字段不同。
     */
    protected function actionInitReviewAttr($arr)
    {
        if (!empty($arr) && is_array($arr)) {
            $attr_arr = $this->_reviewModel->attributes(true);
            $arr_keys = array_keys($arr);
            $attrs = array_diff($arr_keys, $attr_arr);
            $this->_reviewModel->addCustomAttrs($attrs);
        }
    }

    public function getPrimaryKey()
    {
        return '_id';
    }

    /**
     * @property $spu | String.
     * 通过spu找到评论总数。
     */
    protected function actionGetCountBySpu($spu)
    {
        $where = [
            'product_spu' => $spu,
        ];

        if ($this->filterByLang && ($currentLangCode = Yii::$service->store->currentLangCode)) {
            $where['lang_code'] = $currentLangCode;
        }
        $count = $this->_reviewModel->find()->asArray()->where($where)->count();

        return  $count ? $count : 0;
    }

    /**
     * example filter:
     * [
     * 		'numPerPage' 	=> 20,
     * 		'pageNum'		=> 1,
     * 		'orderBy'	=> ['review_date' => SORT_DESC],
     * 		where'			=> [
     * 			['spu' => 'uk10001'],
     * 		],
     * 		'asArray' => true,
     * ]
     * 通过spu找到评论listing.
     */
    protected function actionGetListBySpu($filter)
    {
        if ($this->filterByLang && ($currentLangCode = Yii::$service->store->currentLangCode)) {
            $filter['where'][] = ['lang_code' => $currentLangCode];
        }
        $query = $this->_reviewModel->find();
        $query = Yii::$service->helper->ar->getCollByFilter($query, $filter);

        return [
            'coll' => $query->all(),
            'count'=> $query->count(),
        ];
    }

    /**
     * @property $review_data | Array
     *
     * 增加评论 前台增加评论调用的函数。
     */
    protected function actionAddReview($review_data)
    {
        //$this->initReviewAttr($review_data);
        $model = new $this->_reviewModelName();
        if (isset($review_data[$this->getPrimaryKey()])) {
            unset($review_data[$this->getPrimaryKey()]);
        }
        $model = $this->_reviewModel;
        $review_data['status'] = $model::NOACTIVE_STATUS;

        $review_data['store'] = Yii::$service->store->currentStore;
        $review_data['lang_code'] = Yii::$service->store->currentLangCode;
        $review_data['review_date'] = time();
        if (!Yii::$app->user->isGuest) {
            $identity = Yii::$app->user->identity;
            $user_id = $identity['id'];
            $review_data['user_id'] = $user_id;
        }

        $review_data['ip'] = \fec\helpers\CFunc::get_real_ip();
        $saveStatus = Yii::$service->helper->ar->save($model, $review_data);

        return true;
    }

    /**
     * @property $review_data | Array
     * 保存评论
     */
    protected function actionUpdateReview($review_data)
    {
        //$this->initReviewAttr($review_data);
        $model = $this->_reviewModel->findOne([$this->getPrimaryKey()=> $review_data[$this->getPrimaryKey()]]);
        unset($review_data[$this->getPrimaryKey()]);
        $saveStatus = Yii::$service->helper->ar->save($model, $review_data);

        return true;
    }

    /*
     * example filter:
     * [
     * 		'numPerPage' 	=> 20,
     * 		'pageNum'		=> 1,
     * 		'orderBy'	=> [$this->getPrimaryKey() => SORT_DESC, 'sku' => SORT_ASC ],
     * 		'where'			=> [
                ['>','price',1],
                ['<=','price',10]
     * 			['sku' => 'uk10001'],
     * 		],
     * 	'asArray' => true,
     * ]
     * 查看review 的列表
     */
    protected function actionList($filter)
    {
        $query = $this->_reviewModel->find();
        $query = Yii::$service->helper->ar->getCollByFilter($query, $filter);

        return [
            'coll' => $query->all(),
            'count'=> $query->limit(null)->offset(null)->count(),
        ];
    }

    /**
     * @property $_id | String
     * 后台编辑 通过评论id找到评论
     * 注意：因为每个产品的评论可能加入了新的字段，因此不能使用ActiveRecord的方式取出来，
     * 使用下面的方式可以把字段都取出来。
     */
    protected function actionGetByReviewId($_id)
    {
        return $this->_reviewModel->getCollection()->findOne([$this->getPrimaryKey() => $_id]);
    }

    /**
     * @property $primaryKey | String 主键值
     * get artile model by primary key.
     */
    protected function actionGetByPrimaryKey($primaryKey)
    {
        if ($primaryKey) {
            return $this->_reviewModel->findOne($primaryKey);
        } else {
            return new $this->_reviewModelName();
        }
    }

    /**
     * @property $filter|array
     * get artile collection by $filter
     * example filter:
     * [
     * 		'numPerPage' 	=> 20,
     * 		'pageNum'		=> 1,
     * 		'orderBy'	=> [$this->getPrimaryKey() => SORT_DESC, 'sku' => SORT_ASC ],
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
        return $this->list($filter);
    }

    /**
     * @property $one|array , save one data .
     * @property $originUrlKey|string , article origin url key.
     * 评论，后台审核评论的保存方法。
     * 保存后，把评论信息更新到产品表中。
     */
    protected function actionSave($one)
    {
        $currentDateTime = \fec\helpers\CDate::getCurrentDateTime();
        $primaryVal = isset($one[$this->getPrimaryKey()]) ? $one[$this->getPrimaryKey()] : '';
        $one['status'] = (int) $one['status'];
        $one['rate_star'] = (int) $one['rate_star'];

        if ($primaryVal) {
            $model = $this->_reviewModel->findOne($primaryVal);
            if (!$model) {
                Yii::$service->helper->errors->add('reviewModel '.$this->getPrimaryKey().' is not exist');

                return;
            }
        } else {
            $model = new $this->_reviewModelName();
            $model->created_admin_user_id = \fec\helpers\CUser::getCurrentUserId();
            $primaryVal = new \MongoDB\BSON\ObjectId();
            $model->{$this->getPrimaryKey()} = $primaryVal;
        }
        //$review_data['status'] = $this->_reviewModel->ACTIVE_STATUS;
        $model->review_date = time();
        unset($one[$this->getPrimaryKey()]);
        $saveStatus = Yii::$service->helper->ar->save($model, $one);
        $model->save();
        // 更新评论信息到产品表中。
        $this->updateProductSpuReview($model['product_spu'], $model['lang_code']);

        return true;
    }
    /**
     * @property $ids | Array or String
     * @return boolean
     * 根据提供的ReviewId，删除产品评论
     */
    protected function actionRemove($ids)
    {
        if (!$ids) {
            Yii::$service->helper->errors->add('remove id is empty');

            return false;
        }
        if (is_array($ids) && !empty($ids)) {
            foreach ($ids as $id) {
                $model = $this->_reviewModel->findOne($id);
                if (isset($model[$this->getPrimaryKey()]) && !empty($model[$this->getPrimaryKey()])) {
                    $product_spu = $model['product_spu'];
                    $model->delete();
                    // 更新评论信息到产品表中。
                    $this->updateProductSpuReview($product_spu, $model['lang_code']);
                } else {
                    //throw new InvalidValueException("ID:$id is not exist.");
                    Yii::$service->helper->errors->add("Review Remove Errors:ID $id is not exist.");

                    return false;
                }
            }
        } else {
            $id = $ids;
            $model = $this->_reviewModel->findOne($id);
            if (isset($model[$this->getPrimaryKey()]) && !empty($model[$this->getPrimaryKey()])) {
                $model->delete();
            } else {
                Yii::$service->helper->errors->add("Review Remove Errors:ID:$id is not exist.");

                return false;
            }
        }

        return true;
    }

    /**
     * @property $ids | Array
     * 通过 $ids 数组，批量审核通过评论
     */
    protected function actionAuditReviewByIds($ids)
    {
        $reviewModel = $this->_reviewModel;
        if (is_array($ids) && !empty($ids)) {
            $identity = Yii::$app->user->identity;
            $user_id = $identity['id'];
            foreach ($ids as $id) {
                $model = $this->_reviewModel->findOne($id);
                if ($model[$this->getPrimaryKey()]) {
                    $model->audit_user = $user_id;
                    $model->audit_date = time();
                    $model->status = $reviewModel->getActiveStatus();
                    $model->save();
                    // 更新评论信息到产品表中。
                    $this->updateProductSpuReview($model['product_spu'], $model['lang_code']);
                }
            }
        }
    }

    /**
     * @property $ids | Array
     * 通过 $ids 数组，批量审核评论拒绝
     */
    protected function actionAuditRejectedReviewByIds($ids)
    {
        $reviewModel = $this->_reviewModel;
        if (is_array($ids) && !empty($ids)) {
            $identity = Yii::$app->user->identity;
            $user_id = $identity['id'];
            foreach ($ids as $id) {
                $model = $this->_reviewModel->findOne($id);
                if ($model[$this->getPrimaryKey()]) {
                    $model->audit_user = $user_id;
                    $model->audit_date = time();
                    $model->status = $reviewModel->getRefuseStatus();
                    $model->save();
                    // 更新评论的信息到产品表
                    $this->updateProductSpuReview($model['product_spu'], $model['lang_code']);
                }
            }
        }
    }

    /**
     * @property $spu | String
     * 当评论保存，更新评论的总数，平均评分信息到产品表的所有spu
     */
    protected function actionUpdateProductSpuReview($spu, $lang_code)
    {
        $reviewModel = $this->_reviewModel;
        $filter = [
            'where'            => [
                ['product_spu' => $spu],
                ['status' => $reviewModel->getActiveStatus()],
            ],
        ];
        $coll = $this->coll($filter);
        $count = $coll['count'];
        $data = $coll['coll'];
        $rate_total = 0;
        $rate_lang_total = 0;
        $lang_count = 0;
        if (!empty($data) && is_array($data)) {
            foreach ($data as $one) {
                $rate_total += $one['rate_star'];
                if ($lang_code == $one['lang_code']) {
                    $rate_lang_total += $one['rate_star'];
                    $lang_count++;
                }
            }
        }
        if ($count == 0) {
            $avag_rate = 0;
        } else {
            $avag_rate = ceil($rate_total / $count);
        }
        if ($lang_count == 0) {
            $avag_lang_rate = 0;
        } else {
            $avag_lang_rate = ceil($rate_lang_total / $lang_count);
        }

        Yii::$service->product->updateProductReviewInfo($spu, $avag_rate, $count, $lang_code, $avag_lang_rate, $lang_count);

        return true;
    }

    /**
     * @property $filter|array
     * get artile collection by $filter
     * example filter:
     * [
     * 		'numPerPage' 	=> 20,
     * 		'pageNum'		=> 1,
     * 		'orderBy'	=> [$this->getPrimaryKey() => SORT_DESC, 'sku' => SORT_ASC ],
     *      'where'			=> [
     *          ['>','price',1],
     *          ['<=','price',10]
     * 			['sku' => 'uk10001'],
     * 		],
     * 	    'asArray' => true,
     * ]
     */
    protected function actionGetReviewsByUserId($filter)
    {
        $query = $this->_reviewModel->find();
        $query = Yii::$service->helper->ar->getCollByFilter($query, $filter);

        return [
            'coll' => $query->all(),
            'count'=> $query->count(),
        ];
    }
}
