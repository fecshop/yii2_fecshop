<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\product\review;

//use fecshop\models\mongodb\product\Review as ReviewModel;
use fecshop\services\Service;
use Yii;
use yii\base\InvalidValueException;

/**
 * Product Review Service
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class ReviewMysqldb extends Service implements ReviewInterface
{

    protected $_reviewModelName = '\fecshop\models\mysqldb\product\Review';

    protected $_reviewModel;
    
    protected $_viewService;
    
    public function init()
    {
        parent::init();
        
        list($this->_reviewModelName, $this->_reviewModel) = \Yii::mapGet($this->_reviewModelName);
    }
    
    public function getReviewService()
    {
        if (!$this->_viewService) {
            $this->_viewService = Yii::$service->product->review;
        }
        
        return $this->_viewService;
    }
    
    /**
     * @param $product_id | String, 产品id
     * 是否有评论的权限，如果有，则返回true
     */
    public function isReviewRole($product_id)
    {
        if (!$this->getReviewService()->reviewOnlyOrderedProduct) {
            
            return true;
        }
        $itmes = Yii::$service->order->item->getByProductIdAndCustomerId($product_id, $this->getReviewService()->reviewMonth);
        //var_dump($itmes);exit;
        if ($itmes) {
            
            return true;
        } else {
            
            return false;
        }
    }
    
    /**
     * 得到review noactive status，默认状态
     */
    public function noActiveStatus()
    {
        $model = $this->_reviewModel;
        
        return $model::NOACTIVE_STATUS;
    }

    /**
     * 得到review active status 审核通过的状态
     */
    public function activeStatus()
    {
        $model = $this->_reviewModel;
        
        return $model::ACTIVE_STATUS;
    }

    /**
     * 得到review refuse status 审核拒绝的状态
     */
    public function refuseStatus()
    {
        $model = $this->_reviewModel;
        
        return $model::REFUSE_STATUS;
    }

    

    public function getPrimaryKey()
    {
        return 'id';
    }

    /**
     * @param $spu | String.
     * 通过spu找到评论总数。
     */
    public function getCountBySpu($spu)
    {
        $where = [
            'product_spu' => $spu,
        ];
        if ($this->getReviewService()->filterByLang && ($currentLangCode = Yii::$service->store->currentLangCode)) {
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
    public function getListBySpu($filter)
    {
        if ($this->getReviewService()->filterByLang && ($currentLangCode = Yii::$service->store->currentLangCode)) {
            $filter['where'][] = ['lang_code' => $currentLangCode];
        }
        $query = $this->_reviewModel->find();
        $where = $filter['where'];
        $whereArr = [];
        // 对于 mongodb AR 的or查询的转换。
        if (is_array($where)) {   // mongodb的$or写法
            foreach ($where as $whereOne) {
                if (isset($whereOne['$or']) && is_array($whereOne['$or'])) {
                    $whereArr[] = 'or';
                    foreach ($whereOne['$or'] as $one) {
                        $whereArr[] = $one;
                    }
                    $filter['where'] = [$whereArr];
                }
            }
        }
        $query = Yii::$service->helper->ar->getCollByFilter($query, $filter);
        
        return [
            'coll' => $query->all(),
            'count'=> $query->count(),
        ];
    }

    /**
     * @param $review_data | Array
     *
     * 增加评论 前台增加评论调用的函数。
     */
    public function addReview($review_data)
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
     * @param $review_data | Array
     * 保存评论
     */
    public function updateReview($review_data)
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
    public function lists($filter)
    {
        $query = $this->_reviewModel->find();
        $query = Yii::$service->helper->ar->getCollByFilter($query, $filter);

        return [
            'coll' => $query->all(),
            'count'=> $query->limit(null)->offset(null)->count(),
        ];
    }

    /**
     * @param $_id | String
     * 后台编辑 通过评论id找到评论
     * 注意：因为每个产品的评论可能加入了新的字段，因此不能使用ActiveRecord的方式取出来，
     * 使用下面的方式可以把字段都取出来。
     */
    public function getByReviewId($_id)
    {
        return $this->_reviewModel->getCollection()->findOne([$this->getPrimaryKey() => $_id]);
    }

    /**
     * @param $primaryKey | String 主键值
     * get artile model by primary key.
     */
    public function getByPrimaryKey($primaryKey)
    {
        if ($primaryKey) {
            
            return $this->_reviewModel->findOne($primaryKey);
        } else {
            
            return new $this->_reviewModelName();
        }
    }

    /**
     * @param $filter|array
     * get artile collection by $filter
     * example filter:
     * [
     * 		'numPerPage' 	=> 20,
     * 		'pageNum'		=> 1,
     * 		'orderBy'	=> [$this->getPrimaryKey() => SORT_DESC, 'sku' => SORT_ASC ],
     * 'where'			=> [
     * ['>','price',1],
     * ['<=','price',10]
     * 			['sku' => 'uk10001'],
     * 		],
     * 	'asArray' => true,
     * ]
     */
    public function coll($filter = '')
    {
        return $this->lists($filter);
    }

    /**
     * @param $one|array , save one data .
     * @param $originUrlKey|string , article origin url key.
     * 评论，后台审核评论的保存方法。
     * 保存后，把评论信息更新到产品表中。
     */
    public function save($one)
    {
        $currentDateTime = \fec\helpers\CDate::getCurrentDateTime();
        $primaryVal = isset($one[$this->getPrimaryKey()]) ? $one[$this->getPrimaryKey()] : '';
        $one['status'] = (int) $one['status'];
        $one['rate_star'] = (int) $one['rate_star'];
        if ($primaryVal) {
            $model = $this->_reviewModel->findOne($primaryVal);
            if (!$model) {
                Yii::$service->helper->errors->add('reviewModel {primaryKey} is not exist', ['primaryKey'=>$this->getPrimaryKey()]);

                return;
            }
        } else {
            $model = new $this->_reviewModelName();
            $model->created_admin_user_id = \fec\helpers\CUser::getCurrentUserId();
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
     * @param $ids | Array or String
     * @return boolean
     * 根据提供的ReviewId，删除产品评论
     */
    public function remove($ids)
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
                    Yii::$service->helper->errors->add('Review Remove Errors:ID {id} is not exist.', ['id' => $id]);

                    return false;
                }
            }
        } else {
            $id = $ids;
            $model = $this->_reviewModel->findOne($id);
            if (isset($model[$this->getPrimaryKey()]) && !empty($model[$this->getPrimaryKey()])) {
                $model->delete();
            } else {
                Yii::$service->helper->errors->add('Review Remove Errors:ID:{id} is not exist.', ['id' => $id]);

                return false;
            }
        }

        return true;
    }

    /**
     * @param $ids | Array
     * 通过 $ids 数组，批量审核通过评论
     */
    public function auditReviewByIds($ids)
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
     * @param $ids | Array
     * 通过 $ids 数组，批量审核评论拒绝
     */
    public function auditRejectedReviewByIds($ids)
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
     * @param $spu | String
     * 当评论保存，更新评论的总数，平均评分信息到产品表的所有spu
     */
    public function updateProductSpuReview($spu, $lang_code)
    {
        $reviewModel = $this->_reviewModel;
        $filter = [
            'numPerPage' 	=> 10000,  // mongodb 查询，numPerPage必须设置，如果不设置，默认为20
            'pageNum'		=> 1,
            'where'            => [
                ['product_spu' => $spu],
                ['status' => $reviewModel->getActiveStatus()],
            ],
        ];
        $coll = $this->coll($filter);
        $count = $coll['count'];
        $data = $coll['coll'];
        $rate_total = 0;
        $rate_total_arr['star_0'] = 0;
        $rate_total_arr['star_1'] = 0;
        $rate_total_arr['star_2'] = 0;
        $rate_total_arr['star_3'] = 0;
        $rate_total_arr['star_4'] = 0;
        $rate_total_arr['star_5'] = 0;
        $rate_lang_total = 0;
        $rate_lang_total_arr['star_0'] = 0;
        $rate_lang_total_arr['star_1'] = 0;
        $rate_lang_total_arr['star_2'] = 0;
        $rate_lang_total_arr['star_3'] = 0;
        $rate_lang_total_arr['star_4'] = 0;
        $rate_lang_total_arr['star_5'] = 0;
        $lang_count = 0;
        if (!empty($data) && is_array($data)) {
            foreach ($data as $one) {
                $rate_total += $one['rate_star'];
                $rs = 'star_'.$one['rate_star'];
                $rate_total_arr[$rs] += 1;
                if ($lang_code == $one['lang_code']) {
                    $rate_lang_total += $one['rate_star'];
                    $lang_count++;
                    $rate_lang_total_arr[$rs] += 1;
                }
            }
        }
        if ($count == 0) {
            $avag_rate = 0;
        } else {
            $avag_rate = ceil($rate_total / $count *10) / 10;
        }
        if ($lang_count == 0) {
            $avag_lang_rate = 0;
        } else {
            $avag_lang_rate = ceil($rate_lang_total / $lang_count *10) / 10;
        }
        Yii::$service->product->updateProductReviewInfo($spu, $avag_rate, $count, $lang_code, $avag_lang_rate, $lang_count, $rate_total_arr, $rate_lang_total_arr);

        return true;
    }

    /**
     * @param $filter|array
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
    public function getReviewsByUserId($filter)
    {
        $query = $this->_reviewModel->find();
        $query = Yii::$service->helper->ar->getCollByFilter($query, $filter);

        return [
            'coll' => $query->all(),
            'count'=> $query->count(),
        ];
    }
}
